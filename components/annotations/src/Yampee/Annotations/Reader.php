<?php

/*
 * Yampee Components
 * Open source web development components for PHP 5.
 *
 * @package Yampee Components
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Annotations reader. Read annotations with the parser and check their integrity.
 * Associate those annotations with their objects from the registry.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Annotations_Reader
{
	/**
	 * @var Yampee_Annotations_Parser
	 */
	private $parser;

	/**
	 * Annotations classes.
	 * @var array
	 */
	private $registry;

	/**
	 * Annotations cache (to use in production)
	 * @var array
	 */
	private $cache = array();

	/**
	 * @var bool
	 */
	private $cacheEnabled = true;

	/**
	 * Constructor
	 *
	 * @param bool $cacheEnabled
	 */
	public function __construct($cacheEnabled = true)
	{
		$this->cacheEnabled = $cacheEnabled;
		$this->parser = new Yampee_Annotations_Parser();
		$this->registry = array();
	}

	/**
	 * Read annotations on an element. The element type is automatically found by the method.
	 *
	 * @param object|string $element
	 * @return array
	 */
	public function read($element)
	{
		if (is_object($element)) {
			return $this->readReflector(new ReflectionObject($element));
		}

		if (is_array($element)) {
			if (count($element) != 2) {
				$this->createNotFoundElementException();

				return false;
			}

			if (method_exists($element[0], $element[1])) {
				return $this->readReflector(new ReflectionMethod($element[0], $element[1]));
			} elseif (property_exists($element[0], $element[1])) {
				return $this->readReflector(new ReflectionProperty($element[0], $element[1]));
			}
		}

		if (is_string($element)) {
			if (function_exists($element)) {
				return $this->readReflector(new ReflectionFunction($element));
			} elseif (class_exists($element)) {
				return $this->readReflector(new ReflectionClass($element));
			}
		}

		$this->createNotFoundElementException();

		return false;
	}

	/**
	 * Read annotations on an element, referenced by its reflector.
	 *
	 * @param Reflector $reflector
	 * @return array
	 */
	public function readReflector(Reflector $reflector)
	{
		if ($this->cacheEnabled && isset($this->cache[$reflector->getName()])) {
			return $this->cache[$reflector->getName()];
		}

		$annotationsTypes = $this->parser->parse($reflector->getDocComment());

		$annotations = array();

		foreach ($annotationsTypes as $name => $instances) {
			if (! isset($this->registry[$name])) {
				continue;
			}

			$annotationClass = $this->registry[$name];

			foreach ($instances as $instance) {
				$attributes = $this->checkIntegrity($annotationClass, $reflector, $instance['attributes']);

				$annotationClass = $this->matchAnnotationProperties($annotationClass, $attributes);
				$annotationClass->execute($reflector);

				$annotations[] = $annotationClass;
			}
		}

		return $annotations;
	}

	/**
	 * Register an annotation class. Overwrite any previously registered annotation with the same name.
	 *
	 * @param Yampee_Annotations_Definition_Abstract $annotation
	 * @return Yampee_Annotations_Reader
	 */
	public function registerAnnotation(Yampee_Annotations_Definition_Abstract $annotation)
	{
		$this->registry[$annotation->getAnnotationName()] = $annotation;

		return $this;
	}

	/**
	 * Get the parser instance.
	 *
	 * @return Yampee_Annotations_Parser
	 */
	public function getParser()
	{
		return $this->parser;
	}

	/**
	 * Get the cache content.
	 *
	 * @return array
	 */
	public function getCache()
	{
		return $this->cache;
	}

	/**
	 * Check if the cache is currently enabled of not.
	 *
	 * @return boolean
	 */
	public function isCacheEnabled()
	{
		return $this->cacheEnabled;
	}

	/**
	 * Enable the cache.
	 *
	 * @return Yampee_Annotations_Reader
	 */
	public function enableCache()
	{
		$this->cacheEnabled = true;

		return $this;
	}

	/**
	 * Set an annotation class properties as its provided attributes.
	 *
	 * @param Yampee_Annotations_Definition_Abstract $annotationClass
	 * @param                                        $attributes
	 * @return Yampee_Annotations_Definition_Abstract
	 */
	private function matchAnnotationProperties(Yampee_Annotations_Definition_Abstract $annotationClass, $attributes)
	{
		$annotationClassProperties = $annotationClass->getProperties();

		foreach ($attributes as $name => $value) {
			if (in_array($name, $annotationClassProperties)) {
				$annotationClass->$name = $value;
			}
		}

		return $annotationClass;
	}

	/**
	 * Check annotation integrity based on its definition.
	 *
	 * @param Yampee_Annotations_Definition_Abstract $annotationClass
	 * @param Reflector                              $reflector
	 * @param array                                  $attributes
	 * @return array
	 * @throws LogicException
	 */
	private function checkIntegrity(
		Yampee_Annotations_Definition_Abstract $annotationClass, Reflector $reflector, array $attributes)
	{
		// Check target validity
		$targets = $annotationClass->getTargets();

		if (! empty($targets)) {
			if ($reflector instanceof ReflectionClass
				&& ! in_array(Yampee_Annotations_Definition_Abstract::TARGET_CLASS, $targets)) {
				throw new LogicException(sprintf(
						'Annotation "%s" can not be used on classes.'), $annotationClass->getAnnotationName()
				);
			}

			if ($reflector instanceof ReflectionFunction
				&& ! in_array(Yampee_Annotations_Definition_Abstract::TARGET_FUNCTION, $targets)) {
				throw new LogicException(sprintf(
						'Annotation "%s" can not be used on functions.'), $annotationClass->getAnnotationName()
				);
			}

			if ($reflector instanceof ReflectionMethod
				&& ! in_array(Yampee_Annotations_Definition_Abstract::TARGET_METHOD, $targets)) {
				throw new LogicException(sprintf(
						'Annotation "%s" can not be used on methods.'), $annotationClass->getAnnotationName()
				);
			}

			if ($reflector instanceof ReflectionProperty
				&& ! in_array(Yampee_Annotations_Definition_Abstract::TARGET_PROPERTY, $targets)) {
				throw new LogicException(sprintf(
						'Annotation "%s" can not be used on properties.'), $annotationClass->getAnnotationName()
				);
			}
		}

		// Check attributes rules
		return $this->checkAttributesIntegrity(
			$annotationClass->getAttributesRules(), $attributes, $annotationClass->getAnnotationName()
		);
	}

	/**
	 * Check the attributes integrity based on the annotation class rules.
	 *
	 * @param Yampee_Annotations_Definition_Node $node
	 * @param                                    $attributes
	 * @param                                    $annotationName
	 * @return array
	 */
	private function checkAttributesIntegrity(Yampee_Annotations_Definition_Node $node, $attributes, $annotationName)
	{
		$kept = array();

		if (! $node->getCatchAll()) {
			foreach ($node->getChildren() as $name => $child) {

				// Required ?
				if (! array_key_exists($name, $attributes)) {
					if ($node->isRequired($name)) {
						$this->createDefinitionException(sprintf(
							'Attribute "%s" must be provided in annotation "%s"', $name, $annotationName
						));
					} else {
						if ($child instanceof Yampee_Annotations_Definition_Node) {
							$kept[$name] = array();
						}

						continue;
					}
				}

				if ($child instanceof Yampee_Annotations_Definition_Node) {
					if (! is_array($attributes[$name])) {
						$this->createDefinitionException(sprintf(
							'Attribute "%s" must be of type "array" in annotation "%s"',
							$name, $annotationName
						));
					}

					$kept[$name] = $this->checkAttributesIntegrity(
						$child, $attributes[$name], $annotationName.'['.$name.']'
					);
				} else {
					// Valid type ?
					if (! $this->checkAttributeType($child, $attributes[$name])) {
						$this->createDefinitionException(sprintf(
							'Attribute "%s" must be of type "%s" in annotation "%s"',
							$name, $child['type'], $annotationName
						));
					}

					if (isset($child['name'])) {
						$key = $child['name'];
					} else {
						$key = $name;
					}

					$kept[$key] = $attributes[$name];
				}
			}
		} else {
			$kept = $attributes;
		}

		return $kept;
	}

	/**
	 * Check the validity of an attribute type with its definition.
	 *
	 * @param $definition
	 * @param $value
	 * @return bool
	 */
	private function checkAttributeType($definition, $value)
	{
		if ($definition['type'] == 'numeric' && ! is_numeric($value)) {
			return false;
		}

		if ($definition['type'] == 'boolean' && ! is_bool($value)) {
			return false;
		}

		if ($definition['type'] == 'string' && ! is_string($value)) {
			return false;
		}

		return true;
	}

	/**
	 * Create a definition exception.
	 *
	 * @throws RuntimeException
	 */
	private function createDefinitionException($message)
	{
		throw new RuntimeException($message);
	}

	/**
	 * Create an exception for a not found element.
	 *
	 * @throws InvalidArgumentException
	 */
	private function createNotFoundElementException()
	{
		throw new InvalidArgumentException(
			'Element passed to Yampee_Annotations_Reader::read() does not exists.'
		);
	}
}