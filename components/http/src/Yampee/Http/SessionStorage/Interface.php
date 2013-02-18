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
 * Session storage interface.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
interface Yampee_Http_SessionStorage_Interface
{
	/**
	 * @return Yampee_Http_SessionStorage_Interface
	 */
	public function start();

	/**
	 * @return Yampee_Http_SessionStorage_Interface
	 */
	public function close();

	/**
	 * @param $name
	 * @return bool
	 */
	public function has($name);

	/**
	 * @param string $name
	 * @param mixed  $default
	 * @return mixed
	 */
	public function get($name, $default = null);

	/**
	 * @param string $name
	 * @param mixed  $value
	 * @return Yampee_Http_SessionStorage_Interface
	 */
	public function set($name, $value);

	/**
	 * @param string $name
	 * @return Yampee_Http_SessionStorage_Interface
	 */
	public function remove($name);

	/**
	 * @return array
	 */
	public function all();

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasFlash($name);

	/**
	 * @param string $name
	 * @param mixed  $default
	 * @return mixed
	 */
	public function getFlash($name, $default = null);

	/**
	 * @param string $name
	 * @param mixed  $value
	 * @return Yampee_Http_SessionStorage_Interface
	 */
	public function setFlash($name, $value);

	/**
	 * @param string $name
	 * @return Yampee_Http_SessionStorage_Interface
	 */
	public function removeFlash($name);

	/**
	 * @return array
	 */
	public function allFlashes();

	/**
	 * @return bool
	 */
	public function isStarted();
}