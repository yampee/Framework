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
 * Abstract class that define an annotation.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
abstract class Yampee_Annotations_Definition_Abstract
{
	/**
	 * Target an annotation on a class.
	 */
	const TARGET_CLASS = 'class';

	/**
	 * Target an annotation on a property.
	 */
	const TARGET_PROPERTY = 'property';

	/**
	 * Target an annotation on a method.
	 */
	const TARGET_METHOD = 'method';

	/**
	 * Target an annotation on a function.
	 */
	const TARGET_FUNCTION = 'function';

	/**
	 * Execute an action when the annotation is matched.
	 * Optionnally extended.
	 *
	 * @param Reflector $reflector
	 * @return mixed
	 */
	public function execute(Reflector $reflector) { }

	/**
	 * Define the annotation name.
	 *
	 * @return string
	 */
	abstract public function getAnnotationName();

	/**
	 * Define the annotation attributes rules (types and if they are required or not).
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	abstract public function getAttributesRules();

	/**
	 * Define the allowed annotation targets. If targets are not provided, all target will be accepted.
	 *
	 * @return array
	 */
	abstract public function getTargets();

	/**
	 * Get the annotations properties to match them with attributes.
	 *
	 * @return array
	 */
	final public function getProperties()
	{
		return array_keys(get_object_vars($this));
	}
}