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
class Yampee_Form_Validator_Ip extends Yampee_Form_Validator_Abstract
{
	/**
	 * @param $value
	 * @return boolean
	 */
	public function validate($value)
	{
		return filter_var($value, FILTER_VALIDATE_IP) !== false;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'ip';
	}
}