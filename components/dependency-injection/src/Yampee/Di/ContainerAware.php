<?php

/*
 * Yampee
 * Open source web development framework for PHP 5.2.4 or newer.
 *
 * @package Yampee
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Depencency Injection container aware
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Di_ContainerAware
{
	/**
	 * @var Yampee_Di_Container $container
	 */
	protected $container;

	/**
	 * @param Yampee_Di_Container $container
	 */
	public function __construct(Yampee_Di_Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @param $serviceName
	 * @return mixed
	 */
	public function get($serviceName)
	{
		return $this->container->get($serviceName);
	}

	/**
	 * @param $serviceName
	 * @return bool
	 */
	public function has($serviceName)
	{
		return $this->container->has($serviceName);
	}

	/**
	 * @param $serviceName
	 * @param $object
	 * @return Yampee_Di_ContainerAware
	 */
	public function set($serviceName, $object)
	{
		$this->container->set($serviceName, $object);
		return $this;
	}

	/**
	 * @param $paramName
	 * @return mixed
	 */
	public function getParameter($paramName)
	{
		return $this->container->getParameter($paramName);
	}

	/**
	 * @param $paramName
	 * @return bool
	 */
	public function hasParameter($paramName)
	{
		return $this->container->hasParameter($paramName);
	}

	/**
	 * @param $paramName
	 * @param $object
	 * @return Yampee_Di_ContainerAware
	 */
	public function setParameter($paramName, $object)
	{
		$this->container->setParameter($paramName, $object);
		return $this;
	}

	/**
	 * @param Yampee_Di_Container $container
	 * @return Yampee_Di_ContainerAware
	 */
	public function setContainer(Yampee_Di_Container $container)
	{
		$this->container = $container;

		return $this;
	}

	/**
	 * @return Yampee_Di_Container
	 */
	public function getContainer()
	{
		return $this->container;
	}
}