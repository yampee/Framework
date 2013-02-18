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
 * Route annotation
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Routing_Bridge_Annotation_Route extends Yampee_Annotations_Definition_Abstract
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
	 * Constructor
	 *
	 * @param Yampee_Routing_Router $router
	 */
	public function __construct(Yampee_Routing_Router $router = null)
	{
		if (empty($router)) {
			$router = new Yampee_Routing_Router();
		}

		$this->router = $router;
	}

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
		return array(self::TARGET_METHOD, self::TARGET_CLASS);
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
}