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
 * Route
 */
class Yampee_Routing_Exception_MissingParameter extends RuntimeException
{
	/**
	 * @var Yampee_Routing_Route
	 */
	private $route;

	/**
	 * @var string
	 */
	private $parameter;

	/**
	 * @param Yampee_Routing_Route $route
	 * @param int                  $parameter
	 */
	public function __construct(Yampee_Routing_Route $route, $parameter)
	{
		$this->route = $route;
		$this->parameter = $parameter;

		$this->message = sprintf(
			'Parameter "%s" is required to generate route "%s".',
			$parameter, $route->getName()
		);
	}

	/**
	 * @return string
	 */
	public function getParameter()
	{
		return $this->parameter;
	}

	/**
	 * @return Yampee_Routing_Route
	 */
	public function getRoute()
	{
		return $this->route;
	}
}