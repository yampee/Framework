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
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
abstract class Yampee_Form_Filter_Abstract
{
	/**
	 * Filter the given value
	 *
	 * @param $value
	 * @return mixed
	 */
	abstract public function filter($value);

	/**
	 * Get unique name
	 *
	 * @return string
	 */
	abstract public function getName();
}