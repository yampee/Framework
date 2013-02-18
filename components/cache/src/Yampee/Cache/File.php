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
 * Cache file
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Cache_File
{
	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $cache;

	/**
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->path = (string) $path;
		$this->cache = array();

		$this->open();
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * @return Yampee_Cache_File
	 */
	public function open()
	{
		if (file_exists($this->path)) {
			$this->cache = unserialize(file_get_contents($this->path));
		}

		return $this;
	}

	/**
	 * @return Yampee_Cache_File
	 */
	public function close()
	{
		file_put_contents($this->path, serialize($this->cache));

		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		if (! $this->has($key)) {
			return $default;
		}

		return $this->cache[$key];
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return isset($this->cache[$key]);
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 * @return Yampee_Cache_File
	 */
	public function set($key, $value)
	{
		$this->cache[$key] = $value;

		return $this;
	}

	/**
	 * @param string $key
	 * @return Yampee_Cache_File
	 */
	public function remove($key)
	{
		unset($this->cache[$key]);

		return $this;
	}
}