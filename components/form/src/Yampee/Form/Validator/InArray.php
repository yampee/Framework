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
class Yampee_Form_Validator_InArray extends Yampee_Form_Validator_Abstract
{
	/**
	 * @var array
	 */
	protected $array;

	/**
	 * @param $array
	 * @param $message
	 */
	public function __construct($array, $message = null)
	{
		parent::__construct($message);
		$this->array = $array;
	}

	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		return in_array($value, $this->array);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'in_array';
	}
}