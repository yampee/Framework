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
abstract class Yampee_Form_Validator_Abstract
{
	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @param $message
	 */
	public function __construct($message = null)
	{
		$this->message = (string) $message;

		if (! $this->message) {
			$this->message = $this->getName();
		}
	}

	/**
	 * @return string
	 */
	final public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Check if the given value is valid using this validator
	 *
	 * @param $value
	 * @return boolean
	 */
	abstract public function validate($value);

	/**
	 * Get unique name
	 *
	 * @return string
	 */
	abstract public function getName();
}