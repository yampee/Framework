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
class Yampee_Form_Filter_PregReplace extends Yampee_Form_Filter_Abstract
{
	/**
	 * @var string
	 */
	protected $regex;

	/**
	 * @var string
	 */
	protected $remplacement;

	/**
	 * @param $regex
	 * @param $remplacement
	 */
	public function __construct($regex, $remplacement)
	{
		$this->regex = (string) $regex;
		$this->remplacement = (string) $remplacement;
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function filter($value)
	{
		return preg_replace($this->regex, $this->remplacement, $value);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'preg_replace';
	}
}