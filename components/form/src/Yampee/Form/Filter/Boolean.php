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
class Yampee_Form_Filter_Boolean extends Yampee_Form_Filter_Abstract
{
	/**
	 * @param $value
	 * @return mixed
	 */
	public function filter($value)
	{
		return (boolean) $value;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'boolean';
	}
}