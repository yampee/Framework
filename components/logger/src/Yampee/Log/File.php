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
 * Log file
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Log_File
{
	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $log;

	/**
	 * @var boolean
	 */
	protected $store;

	/**
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->path = (string) $path;
		$this->log = array();
		$this->store = true;

		if (! file_exists($this->path)) {
			if (! file_exists(dirname($this->path))) {
				mkdir(dirname($this->path), 777, true);
			}

			file_put_contents($this->path, '');
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		$this->store();
	}

	/**
	 * @return Yampee_Log_File
	 */
	public function store()
	{
		if ($this->store) {
			file_put_contents($this->path, file_get_contents($this->path).implode("\n", $this->log)."\n");
		}

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function error($message)
	{
		$this->log('[Error] '.$message);

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function emergency($message)
	{
		$this->log('[EMERGENCY] '.$message);

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function critical($message)
	{
		$this->log('[Critical] '.$message);

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function warning($message)
	{
		$this->log('[Warning] '.$message);

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function alert($message)
	{
		$this->log('[Alert] '.$message);

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function notice($message)
	{
		$this->log('[Notice] '.$message);

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function info($message)
	{
		$this->log('[Info] '.$message);

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function debug($message)
	{
		$this->log('[Debug] '.$message);

		return $this;
	}

	/**
	 * @return Yampee_Log_Logger
	 */
	public function clearCurrentScriptLog()
	{
		$this->log = array();

		return $this;
	}

	/**
	 * @return array
	 */
	public function getCurrentScriptLog()
	{
		return $this->log;
	}

	/**
	 * @return Yampee_Log_File
	 */
	public function enable()
	{
		$this->store = true;

		return $this;
	}

	/**
	 * @return Yampee_Log_File
	 */
	public function disable()
	{
		$this->store = false;

		return $this;
	}

	/**
	 * Store a message and its context
	 *
	 * @param $message
	 * @return Yampee_Log_File
	 */
	protected function log($message)
	{
		$this->log[] = '['.date('d-m-Y H:i:s').'] '.$message;

		return $this;
	}
}