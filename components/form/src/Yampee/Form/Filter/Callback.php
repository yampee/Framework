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
class Yampee_Form_Filter_Callback extends Yampee_Form_Filter_Abstract
{
	/**
	 * @var callable
	 */
	protected $callback;

	/**
	 * @param callable $callback
	 */
	public function __construct($callback)
	{
		$this->callback = $callback;
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function filter($value)
	{
		return call_user_func($this->callback, $value);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'callback';
	}
}