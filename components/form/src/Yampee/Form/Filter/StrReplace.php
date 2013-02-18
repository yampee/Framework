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
class Yampee_Form_Filter_StrReplace extends Yampee_Form_Filter_Abstract
{
	/**
	 * @var array
	 */
	protected $remplacements;

	/**
	 * @param array $remplacements
	 */
	public function __construct(array $remplacements)
	{
		$this->remplacements = $remplacements;
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function filter($value)
	{
		return str_replace(array_keys($this->remplacements), array_values($this->remplacements), $value);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'str_replace';
	}
}