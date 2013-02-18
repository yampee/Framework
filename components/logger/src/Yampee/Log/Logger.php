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
	 * @var Yampee_Log_Storage_Interface
	 */
	protected $storage;

	/**
	 * @var array
	 */
	protected $currentScriptLog;

	/**
	 * @var boolean
	 */
	protected $store = true;

	/**
	 * @param Yampee_Log_Storage_Interface $storage
	 */
	public function __construct(Yampee_Log_Storage_Interface $storage)
	{
		$this->storage = $storage;
		$this->currentScriptLog = array();
	}

	/**
	 * Destructor: save logs
	 */
	public function __destruct()
	{
		$this->store();
	}

	/**
	 * Store logs manually
	 */
	public function store()
	{
		if (! $this->store) {
			return;
		}

		$this->storage->store($this->currentScriptLog);
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function error($message)
	{
		$this->currentScriptLog[] = '[Error] '.$message;

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function emergency($message)
	{
		$this->currentScriptLog[] = '[EMERGENCY] '.$message;

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function critical($message)
	{
		$this->currentScriptLog[] = '[Critical] '.$message;

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function warning($message)
	{
		$this->currentScriptLog[] = '[Warning] '.$message;

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function alert($message)
	{
		$this->currentScriptLog[] = '[Alert] '.$message;

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function notice($message)
	{
		$this->currentScriptLog[] = '[Notice] '.$message;

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function info($message)
	{
		$this->currentScriptLog[] = '[Info] '.$message;

		return $this;
	}

	/**
	 * @param $message
	 * @return Yampee_Log_Logger
	 */
	public function debug($message)
	{
		$this->currentScriptLog[] = '[Debug] '.$message;

		return $this;
	}

	/**
	 * CLear the current script logs
	 *
	 * @return Yampee_Log_Logger
	 */
	public function clearCurrentScriptLog()
	{
		$this->currentScriptLog = array();

		return $this;
	}

	/**
	 * @return Yampee_Log_Storage_Interface
	 */
	public function getStorage()
	{
		return $this->storage;
	}

	/**
	 * @param Yampee_Log_Storage_Interface $storage
	 * @return Yampee_Log_Logger
	 */
	public function setStorage(Yampee_Log_Storage_Interface $storage)
	{
		$this->storage = $storage;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getCurrentScriptLog()
	{
		return $this->currentScriptLog;
	}

	/**
	 * @return Yampee_Log_Logger
	 */
	public function enable()
	{
		$this->store = true;

		return $this;
	}

	/**
	 * @return Yampee_Log_Logger
	 */
	public function disable()
	{
		$this->store = false;

		return $this;
	}
}