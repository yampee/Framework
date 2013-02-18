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
class Yampee_Form_Validator_Callback extends Yampee_Form_Validator_Abstract
{
	/**
	 * @var callable
	 */
	protected $callback;

	/**
	 * @param callable $callback
	 * @param null $message
	 */
	public function __construct($callback, $message = null)
	{
		parent::__construct($message);
		$this->callback = $callback;
	}

	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		return call_user_func($this->callback, $value);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'between';
	}
}