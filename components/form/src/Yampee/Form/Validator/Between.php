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
class Yampee_Form_Validator_MaxLength extends Yampee_Form_Validator_Abstract
{
	/**
	 * @var integer
	 */
	protected $min;

	/**
	 * @var integer
	 */
	protected $max;

	/**
	 * @param integer $min
	 * @param integer $max
	 * @param null $message
	 */
	public function __construct($min, $max, $message = null)
	{
		parent::__construct($message);
		$this->min = (int) $min;
		$this->max = (int) $max;
	}

	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		$value = (int) $value;

		return $value >= $this->min && $value <= $this->max;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'between';
	}
}