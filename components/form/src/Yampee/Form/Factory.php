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
 * Form factory, to create builder instances and to use DI container.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Form_Factory
{
	/**
	 * @param string $method
	 * @return Yampee_Form_Form
	 */
	public function createFormBuilder($method = 'post')
	{
		return new Yampee_Form_Form($method);
	}
}