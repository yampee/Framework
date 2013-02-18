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
 * Depencency Injection container
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Di_Container
{
	/**
	 * @var array $services
	 */
	private $services;

	/**
	 * @var array $tags
	 */
	private $tags;

	/**
	 * @var array $parameters
	 */
	private $parameters;

	/**
	 * @var array $definitions
	 */
	private $definitions;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->services = array();
		$this->tags = array();
		$this->parameters = array();
		$this->definitions = array();

		$this->set('container', $this);
		$this->set('container.dumper', new Yampee_Di_Dumper());
	}

	/**
	 * Build the container
	 *
	 * @throws InvalidArgumentException
	 */
	public function build()
	{
		$this->checkBuildPossibility();

		$parametersRemplacements = array();

		foreach ($this->parameters as $paramName => $paramValue) {
			$parametersRemplacements['%'.$paramName.'%'] = $paramValue;
		}

		$this->parameters = $this->remplaceParameters(
			$this->parameters,
			array_keys($parametersRemplacements),
			array_values($parametersRemplacements)
		);

		foreach ($this->definitions as $name => $definition) {
			$this->buildDefinition($name);
		}
	}

	/**
	 * @param string $name
	 * @param array  $servicesDefinition
	 * @return Yampee_Di_Container
	 */
	public function registerDefinition($name, array $servicesDefinition)
	{
		$this->definitions[(string) $name] = $servicesDefinition;

		return $this;
	}

	/**
	 * @param array $servicesDefinitions
	 * @return Yampee_Di_Container
	 */
	public function registerDefinitions(array $servicesDefinitions)
	{
		$this->definitions = array_merge($this->definitions, $servicesDefinitions);

		return $this;
	}

	/**
	 * @param array $definitions
	 * @return Yampee_Di_Container
	 */
	public function setDefinitions(array $definitions)
	{
		$this->definitions = $definitions;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getDefinitions()
	{
		return $this->definitions;
	}

	/**
	 * @return array
	 */
	public function getAll()
	{
		return $this->services;
	}

	/**
	 * @param $serviceName
	 * @return mixed
	 */
	public function get($serviceName)
	{
		return $this->services[$serviceName];
	}

	/**
	 * @param $serviceName
	 * @return bool
	 */
	public function has($serviceName)
	{
		return isset($this->services[$serviceName]);
	}

	/**
	 * @param string $serviceName
	 * @param object $object
	 * @return Yampee_Di_Container
	 */
	public function set($serviceName, $object)
	{
		$this->services[$serviceName] = $object;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAllParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param $paramName
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function getParameter($paramName)
	{
		if (! $this->hasParameter($paramName)) {
			throw new InvalidArgumentException(sprintf(
				'Non-existent parameter "%s" requested', $paramName
			));
		}

		return $this->parameters[$paramName];
	}

	/**
	 * @param $paramName
	 * @return bool
	 */
	public function hasParameter($paramName)
	{
		return isset($this->parameters[$paramName]);
	}

	/**
	 * @param string $paramName
	 * @param mixed $value
	 * @return Yampee_Di_Container
	 */
	public function setParameter($paramName, $value)
	{
		$this->parameters[$paramName] = $value;
		return $this;
	}

	/**
	 * @param array $parameters
	 * @return Yampee_Di_Container
	 */
	public function setParameters(array $parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * Get the list of tags on a given service
	 *
	 * @param string $serviceName
	 * @return array
	 */
	public function getTags($serviceName)
	{
		return $this->tags[$serviceName];
	}

	/**
	 * Get the list of services names using a given tag
	 *
	 * @param string $tag
	 * @return array
	 */
	public function findNamesByTag($tag)
	{
		$services = array();

		foreach ($this->tags as $serviceName => $tags) {
			foreach ($tags as $serviceTag) {
				if ($serviceTag['name'] == $tag) {
					$services[] = $serviceName;
					continue;
				}
			}
		}

		return $services;
	}

	/**
	 * Get the list of services using a given tag
	 *
	 * @param string $tag
	 * @return array
	 */
	public function findByTag($tag)
	{
		$services = array();

		foreach ($this->tags as $serviceName => $tags) {
			foreach ($tags as $serviceTag) {
				if ($serviceTag['name'] == $tag) {
					$services[] = $this->get($serviceName);
					continue;
				}
			}
		}

		return $services;
	}

	/**
	 * Check if the given service have the given tag
	 *
	 * @param $serviceName
	 * @param $tagName
	 * @return boolean
	 */
	public function hasTag($serviceName, $tagName)
	{
		foreach ($this->tags[$serviceName] as $name => $tag) {
			if ($name == $tagName) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param array $parameters
	 * @param array $patterns
	 * @param array $values
	 * @return array
	 */
	protected function remplaceParameters($parameters, $patterns, $values)
	{
		foreach ($parameters as $name => $value) {
			if (is_array($value)) {
				$parameters[$name] = $this->remplaceParameters($value, $patterns, $values);
			} else {
				$parameters[$name] = str_replace($patterns, $values, $value);
			}
		}

		return $parameters;
	}

	/**
	 * Check the build possibility by tracking recusrives references.
	 *
	 * @throws InvalidArgumentException
	 */
	protected function checkBuildPossibility()
	{
		$definitions = array();

		foreach ($this->definitions as $name => $definition) {
			$references = array();

			if (! isset($definition['arguments'])) {
				continue;
			}

			foreach ($definition['arguments'] as $argument) {
				if (is_string($argument) && substr($argument, 0, 1) == '@') {
					$references[] = substr($argument, 1);
				}
			}

			$definitions[$name] = array(
				'name' => $name,
				'references' => $references,
			);
		}

		foreach ($definitions as $definition) {
			foreach ($definition['references'] as $reference) {
				if (isset($definitions[$reference])
					&& in_array($definition['name'], $definitions[$reference]['references'])) {
						throw new LogicException(sprintf(
							'Circular reference betwenn %s and %s. The container build can not be completed.',
							$definition['name'], $definitions[$reference]['name']
						));
				}
			}
		}
	}

	/**
	 * Build a given definition
	 *
	 * @throws InvalidArgumentException
	 */
	protected function buildDefinition($serviceName)
	{
		$serviceDefinition = array_merge(array(
			'class' => '',
			'arguments' => array()
		), $this->definitions[$serviceName]);

		if (! class_exists($serviceDefinition['class']) && isset($serviceDefinition['file'])) {
			require $serviceDefinition['file'];
		}

		if (! class_exists($serviceDefinition['class'])) {
			throw new InvalidArgumentException(sprintf(
				'Class %s not found in service %s.',
				$serviceDefinition['class'], $serviceName
			));
		}

		$arguments = array();
		$i = 1;

		foreach ($serviceDefinition['arguments'] as $argument) {
			if (is_string($argument) && substr($argument, 0, 1) == '%' && substr($argument, -1) == '%') {
				$paramName = substr($argument, 1, -1);

				if (! $this->hasParameter($paramName)) {
					throw new InvalidArgumentException(sprintf(
						'Config element %s does not exists in service definition %s (argument %s)',
						$paramName, $serviceName, $i
					));
				}

				$arguments[] = $this->getParameter($paramName);
			} elseif (is_string($argument) && substr($argument, 0, 1) == '@') {
				$referenceName = substr($argument, 1);

				if (isset($this->definitions[$referenceName]) && ! $this->has($referenceName)) {
					$this->buildDefinition($referenceName);
				}

				if (! $this->has($referenceName)) {
					throw new InvalidArgumentException(sprintf(
						'Reference at %s is not available in service definition %s (argument %s)',
						$referenceName, $serviceName, $i
					));
				}

				$arguments[] = $this->get($referenceName);
			} else {
				$parametersRemplacements = array();

				foreach ($this->parameters as $paramName => $paramValue) {
					$parametersRemplacements['%'.$paramName.'%'] = $paramValue;
				}

				$arguments[] = $this->remplaceParameters(
					$argument,
					array_keys($parametersRemplacements),
					array_values($parametersRemplacements)
				);
			}

			$i++;
		}

		$reflection = new ReflectionClass($serviceDefinition['class']);
		$instance = $reflection->newInstanceArgs($arguments);

		if (isset($serviceDefinition['calls']) && ! empty($serviceDefinition['calls'])) {
			foreach ($serviceDefinition['calls'] as $methodName => $methodArgs) {
				$method = new ReflectionMethod($instance, $methodName);
				$method->invokeArgs($instance, $methodArgs);
			}
		}

		if (isset($serviceDefinition['tags']) && ! empty($serviceDefinition['tags'])) {
			foreach ($serviceDefinition['tags'] as $tag) {
				$this->tags[$serviceName][] = $tag;
			}
		}

		$this->set($serviceName, $instance);
	}
}