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
 * Service annotation
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Di_Bridge_Annotation_Service extends Yampee_Annotations_Definition_Abstract
{
	/**
	 * @var Yampee_Di_Container
	 */
	protected $container;

	/*
	 * Annotation parameters
	 */
	public $name;
	public $arguments;
	public $calls;
	public $tags;

	/**
	 * Constructor
	 *
	 * @param Yampee_Di_Container $container
	 */
	public function __construct(Yampee_Di_Container $container = null)
	{
		if (empty($container)) {
			$container = new Yampee_Di_Container();
		}

		$this->container = $container;
	}

	/**
	 * Execute an action when the annotation is matched.
	 *
	 * @param Reflector $reflector
	 * @return mixed
	 */
	public function execute(Reflector $reflector)
	{
		$definition = array(
			'class' => $reflector->getName(),
			'arguments' => $this->arguments,
			'calls' => $this->calls,
			'tags' => $this->tags,
		);

		$this->container->registerDefinition($this->name, $definition);
	}

	/**
	 * Define the annotation name.
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'Service';
	}

	/**
	 * Define the annotation attributes rules (types and if they are required or not).
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function getAttributesRules()
	{
		$rootNode = new Yampee_Annotations_Definition_RootNode();

		$rootNode
			// Service name
			->anonymousAttr(0, 'name', true)

			// Service arguments
			->arrayAttr('arguments', false)
				->catchAll()
			->end()

			// Service calls
			->arrayAttr('calls', false)
				->catchAll()
			->end()

			// Service tags
			->arrayAttr('tags', false)
				->catchAll()
			->end();

		return $rootNode;
	}

	/**
	 * Define the allowed annotation targets. If targets are not provided, all target will be accepted.
	 *
	 * @return array
	 */
	public function getTargets()
	{
		return array(self::TARGET_CLASS);
	}
}