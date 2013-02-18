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
class Yampee_Form_Validator_Regex extends Yampee_Form_Validator_Abstract
{
	/**
	 * @var string
	 */
	protected $regex;

	/**
	 * @param $regex
	 * @param $message
	 */
	public function __construct($regex, $message = null)
	{
		parent::__construct($message);
		$this->regex = $regex;
	}

	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		return is_string($value) && preg_match($this->regex, $value);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'regex';
	}
}