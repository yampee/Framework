<?php

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class RouteAnnotation extends Yampee_Annotations_Definition_Abstract
{
	/**
	 * @var Yampee_Routing_Router
	 */
	protected $router;

	/*
	 * Annotation parameters
	 */
	public $pattern;
	public $name;
	public $defaults;
	public $requirements;

	/**
	 * Return the annotation name: here, we will use the annotation as @Route()
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'Route';
	}

	/**
	 * Return the list of authorized targets. You can use:
	 *      self::TARGET_CLASS, self::TARGET_PROPERTY,
	 *      self::TARGET_METHOD, self::TARGET_FUNCTION
	 *
	 * An empty array will allow any target.
	 *
	 * @return array
	 */
	public function getTargets()
	{
		return array(self::TARGET_METHOD);
	}

	/**
	 * Return the attributes rules.
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function getAttributesRules()
	{
		$rootNode = new Yampee_Annotations_Definition_RootNode();

		$rootNode
			->anonymousAttr(0, 'pattern', true)
			->stringAttr('name', false)
			->arrayAttr('defaults', false)
				->catchAll()
			->end()
			->arrayAttr('requirements', false)
				->catchAll()
			->end();

		return $rootNode;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getDefaults()
	{
		return $this->defaults;
	}

	/**
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @return array
	 */
	public function getRequirements()
	{
		return $this->requirements;
	}
}