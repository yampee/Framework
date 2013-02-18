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
 * Router to match routes with URL
 */
class Yampee_Routing_Router
{
	/**
	 * Routes
	 * @var array
	 */
	private $routes;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->routes = array();
	}

	/**
	 * @param Yampee_Routing_Route $route
	 * @return Yampee_Routing_Router
	 */
	public function addRoute(Yampee_Routing_Route $route)
	{
		$this->routes[$route->getName()] = $route;

		return $this;
	}

	/**
	 * @param Yampee_Routing_Route|string $route
	 * @return Yampee_Routing_Router
	 * @throws InvalidArgumentException
	 */
	public function removeRoute($route)
	{
		if ($route instanceof Yampee_Routing_Route) {
			$route = $route->getName();
		}

		if (! is_string($route)) {
			throw new InvalidArgumentException(sprintf(
				'Argument 1 passed to Ympee_Routing_Router::removeRoute() must be a string or a
				Yampee_Routing_Route object (%s given).', gettype($route)
			));
		}

		return $this;
	}

	/**
	 * @param string $url
	 * @return Yampee_Routing_Route
	 */
	public function find($url)
	{
		foreach($this->routes as $route) {
			if($this->match($route->getName(), $url)) {
				return $route;
			}
		}

		return false;
	}

	/**
	 * @param string $routeName
	 * @param string $url
	 * @return bool
	 */
	public function match($routeName, $url)
	{
		return $this->get($routeName)->match($url);
	}

	/**
	 * @param       $routeName
	 * @param array $parameters
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function generate($routeName, array $parameters = array())
	{
		if (! $this->has($routeName)) {
			throw new InvalidArgumentException(sprintf(
				'Route "%s" does not exists and can not be generated', $routeName
			));
		}

		return $this->get($routeName)->generate($parameters);
	}

	/**
	 * @param string $routeName
	 * @return boolean
	 */
	public function has($routeName)
	{
		return isset($this->routes[$routeName]);
	}

	/**
	 * @param string $routeName
	 * @return Yampee_Routing_Route
	 */
	public function get($routeName)
	{
		return $this->routes[$routeName];
	}
}