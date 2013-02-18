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
class Yampee_Form_Validator_Numeric extends Yampee_Form_Validator_Abstract
{
	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		return is_numeric($value);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'numeric';
	}
}