<?php

/*
 * Yampee Framework
 * Open source web development framework for PHP 5.
 *
 * @package Yampee Framework
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

class Yampee_Twig_Core extends Yampee_Twig_Extension
{
	/**
	 * @var string
	 */
	protected $rootUrl;

	/**
	 * @param $rootUrl
	 */
	public function __construct($rootUrl)
	{
		$this->rootUrl = $rootUrl;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'yampee_core';
	}

	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return array(
			new Twig_SimpleFunction('asset', array($this, 'getAsset')),
		);
	}

	/**
	 * @param $path
	 * @return string
	 */
	public function getAsset($path)
	{
		return $this->rootUrl.'/web/'.$path;
	}
}