<?php

/*
 * Yampee Framework
 * Open source web development framework for PHP 5.
 *
 * @package Yampee Framework
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Security context
 */
class Yampee_Security_UserToken
{
	/**
	 * @var object
	 */
	protected $data;

	/**
	 * @var boolean
	 */
	protected $isConnected;

	/**
	 * @var string
	 */
	protected $locale;

	/**
	 * @param bool   $isConnected
	 * @param string $locale
	 */
	public function __construct($isConnected = false, $locale = 'en')
	{
		$this->isConnected = $isConnected;
		$this->locale = $locale;
	}

	/**
	 * @return object
	 */
	public function getUser()
	{
		return $this->data;
	}

	/**
	 * @return bool
	 */
	public function isConnected()
	{
		return $this->isConnected;
	}

	/**
	 * @param $data
	 * @throws InvalidArgumentException
	 */
	public function connect($data)
	{
		if (! is_array($data) || ! is_object($data)) {
			throw new InvalidArgumentException(sprintf(
				'Argument 1 of Yampee_Security_User::connect() must be an array or an object (%s given)',
				gettype($data)
			));
		}

		$this->data = $data;
		$this->isConnected = true;
	}

	/**
	 * @param string $locale
	 * @return Yampee_Security_UserToken
	 */
	public function setLocale($locale)
	{
		$this->locale = (string) $locale;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}
}