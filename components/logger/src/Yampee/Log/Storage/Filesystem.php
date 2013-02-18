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
 * Log storage that use files
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Log_Storage_Filesystem implements Yampee_Log_Storage_Interface
{
	/**
	 * @var string
	 */
	protected $logFile;

	/**
	 * @param string $logFile
	 */
	public function __construct($logFile)
	{
		$this->logFile = (string) $logFile;

		if (! file_exists($this->logFile)) {
			if (! file_exists(dirname($this->logFile))) {
				mkdir(dirname($this->logFile), 777, true);
			}

			file_put_contents($this->logFile, '');
		}
	}

	/**
	 * Store a message and its context
	 *
	 * @param array $messages
	 * @return Yampee_Log_Storage_Filesystem
	 */
	public function store($messages)
	{
		$message = '';

		foreach ((array) $messages as $m) {
			$message .= '['.date('d-m-Y H:i:s').'] '.$m."\n";
		}

		file_put_contents($this->logFile, file_get_contents($this->logFile).$message);

		return $this;
	}

	/**
	 * @param string $logFile
	 * @return Yampee_Log_Storage_Filesystem
	 */
	public function setLogFile($logFile)
	{
		$this->logFile = $logFile;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLogFile()
	{
		return $this->logFile;
	}
}