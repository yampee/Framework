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
class Yampee_Form_Validator_Alnum extends Yampee_Form_Validator_Abstract
{
	/**
	 * @var string
	 */
	protected $regex;

	/**
	 * @param bool $allowWithespaces
	 * @param bool $allowDashes
	 * @param null $message
	 */
	public function __construct($allowWithespaces = false, $allowDashes = false, $message = null)
	{
		parent::__construct($message);

		$this->regex = 'a-z0-9';

		if ($allowDashes) {
			$this->regex .= '\-';
		}
		if ($allowWithespaces) {
			$this->regex .= '\s';
		}

		$this->regex = '#^['.$this->regex.']+$#i';
	}

	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		return preg_match($this->regex, $value);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'alnum';
	}
}