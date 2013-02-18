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
 * Simple sessions manager.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Http_Session
{
	/**
	 * @var Yampee_Http_SessionStorage_Interface
	 */
	protected $storage;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->storage = new Yampee_Http_SessionStorage_Native();
		$this->start();
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * @return Yampee_Http_Session
	 */
	public function start()
	{
		$this->storage->start();

		return $this;
	}

	/**
	 * @return Yampee_Http_Session
	 */
	public function close()
	{
		$this->storage->close();

		return $this;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function has($name)
	{
		return $this->storage->has($name);
	}

	/**
	 * @param string $name
	 * @param mixed  $default
	 * @return mixed
	 */
	public function get($name, $default = null)
	{
		return $this->storage->get($name, $default);
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 * @return Yampee_Http_Session
	 */
	public function set($name, $value)
	{
		$this->storage->set($name, $value);

		return $this;
	}

	/**
	 * @param string $name
	 * @return Yampee_Http_Session
	 */
	public function remove($name)
	{
		$this->storage->remove($name);

		return $this;
	}

	/**
	 * @return array
	 */
	public function all()
	{
		return $this->storage->all();
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasFlash($name)
	{
		return $this->storage->hasFlash($name);
	}

	/**
	 * @param string $name
	 * @param mixed  $default
	 * @return mixed
	 */
	public function getFlash($name, $default = null)
	{
		return $this->storage->getFlash($name, $default);
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 * @return Yampee_Http_Session
	 */
	public function setFlash($name, $value)
	{
		$this->storage->setFlash($name, $value);

		return $this;
	}

	/**
	 * @param string $name
	 * @return Yampee_Http_Session
	 */
	public function removeFlash($name)
	{
		$this->storage->removeFlash($name);

		return $this;
	}

	/**
	 * @return array
	 */
	public function allFlashes()
	{
		return $this->storage->allFlashes();
	}

	/**
	 * @return bool
	 */
	public function isStarted()
	{
		return $this->storage->isStarted();
	}

	/**
	 * @return Yampee_Http_SessionStorage_Interface
	 */
	public function getStorage()
	{
		return $this->storage;
	}

	/**
	 * @param Yampee_Http_SessionStorage_Interface $storage
	 * @return Yampee_Http_Session
	 */
	public function setStorage(Yampee_Http_SessionStorage_Interface $storage)
	{
		$this->storage = $storage;

		return $this;
	}
}