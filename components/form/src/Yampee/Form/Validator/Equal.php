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
class Yampee_Form_Validator_Equal extends Yampee_Form_Validator_Abstract
{
	/**
	 * @var string
	 */
	protected $value;

	/**
	 * @param $value
	 * @param $message
	 */
	public function __construct($value, $message = null)
	{
		parent::__construct($message);
		$this->value = $value;
	}

	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		return $value == $this->value;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'equal';
	}
}