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
 * Class to benchmark the script and profile loading time.
 */
class Yampee_Benchmark
{
	/**
	 * @var array
	 */
	protected $times;

	/**
	 * @var integer
	 */
	protected $startMemoryUsage;

	/**
	 * Start the benhmark.
	 */
	public function __construct()
	{
		$this->times = array('start' => microtime(true));
		$this->startMemoryUsage = memory_get_usage();
	}

	/**
	 * Mark a position in the script.
	 *
	 * @param $name
	 */
	public function markAs($name)
	{
		$this->times[$name] = (microtime(true) - $this->times['start']) * 1000;
	}

	/**
	 * @return array
	 */
	public function getTimes()
	{
		return $this->times;
	}

	/**
	 * @param string $key
	 * @return float
	 */
	public function get($key)
	{
		return $this->times[$key];
	}

	/**
	 * @return array
	 */
	public function getMemoryUsage()
	{
		return (memory_get_usage() - $this->startMemoryUsage) / (1024 * 1024);
	}

	/**
	 * @return void
	 */
	public function kernelLoaded()
	{
		$this->markAs('kernel.loaded');
	}

	/**
	 * @return void
	 */
	public function kernelRequest()
	{
		$this->markAs('kernel.request');
	}

	/**
	 * @return void
	 */
	public function kernelAction()
	{
		$this->markAs('kernel.action');
	}

	/**
	 * @return void
	 */
	public function kernelResponse()
	{
		$this->markAs('kernel.response');
	}
}