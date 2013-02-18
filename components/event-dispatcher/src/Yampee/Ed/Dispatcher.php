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
 * Event dispatcher main object. Connect listeners to events and send those events.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Ed_Dispatcher
{
	/**
	 * @var array
	 */
	protected $listeners;

	/**
	 * @var int
	 */
	protected $currentIndex;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->listeners = array();
		$this->currentIndex = 0;
	}

	/**
	 * @param string $eventName
	 * @param object $object
	 * @param string $method
	 * @param int    $index
	 * @return Yampee_Ed_Dispatcher
	 */
	public function addListener($eventName, $object, $method, $index = null)
	{
		$this->listeners[$eventName][get_class($object).'::'.$method] = array(
			'object' => $object,
			'method' => $method,
			'index' => ($index === null) ? $this->currentIndex : $index
		);

		$this->currentIndex++;

		return $this;
	}

	/**
	 * @param string $eventName
	 * @param object $object
	 * @param string $method
	 * @return bool
	 */
	public function removeListener($eventName, $object, $method)
	{
		if (! isset($this->listeners[$eventName][get_class($object).'::'.$method])) {
			return false;
		}

		unset($this->listeners[$eventName][get_class($object).'::'.$method]);

		return true;
	}

	/**
	 * Notify an event
	 *
	 * @param       $eventName
	 * @param array $parameters
	 */
	public function notify($eventName, array $parameters = array())
	{
		if (! isset($this->listeners[$eventName])) {
			return;
		}

		$listeners = $this->listeners[$eventName];
		$indexes = array();

		foreach ($listeners as $name => $listener) {
			$indexes[$name] = $listener['index'];
		}

		asort($indexes);

		$callables = array();

		foreach ($indexes as $callable => $index) {
			$callables[] = $listeners[$callable];
		}

		foreach ($callables as $callable) {
			$reflection = new ReflectionMethod($callable['object'], $callable['method']);
			$newParameters = (array) $reflection->invokeArgs($callable['object'], $parameters);

			if (! is_null($newParameters)) {
				$parameters = $newParameters;
			}
		}
	}
}