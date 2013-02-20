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
 * Logger.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Log_Logger
{
	/**
	 * @var string
	 */
	protected $directory;

	/**
	 * @var array
	 */
	protected $openedFiles;

	/**
	 * Constructor
	 *
	 * @param string $directory
	 */
	public function __construct($directory)
	{
		$this->directory = $directory;
	}

	/**
	 * @param $name
	 * @return Yampee_Log_File
	 */
	public function getFile($name)
	{
		if (isset($this->openedFiles[$name])) {
			return $this->openedFiles[$name];
		}

		if (! $this->hasFile($name)) {
			file_put_contents($this->directory.'/'.$name, serialize(array()));
		}

		$this->openedFiles[$name] = new Yampee_Log_File($this->directory.'/'.$name);

		return $this->openedFiles[$name];
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasFile($name)
	{
		return file_exists($this->directory.'/'.$name);
	}

	/**
	 * @param $name
	 * @return Yampee_Cache_Manager
	 */
	public function deleteFile($name)
	{
		if ($this->hasFile($name)) {
			unlink($this->directory.'/'.$name);
		}

		return $this;
	}
}