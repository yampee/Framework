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
class Yampee_Form_Validator_MinLength extends Yampee_Form_Validator_Abstract
{
	/**
	 * @var integer
	 */
	protected $length;

	/**
	 * @param $length
	 * @param $message
	 */
	public function __construct($length, $message = null)
	{
		parent::__construct($message);
		$this->length = (int) $length;
	}

	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		return is_string($value) && strlen($value) >= $this->length;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'min_length';
	}
}