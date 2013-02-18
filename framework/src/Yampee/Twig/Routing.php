<?php

/*
 * Yampee Framework
 * Open source web development framework for PHP 5.
 *
 * @package Yampee Framework
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

class Yampee_Twig_Routing extends Twig_Extension
{
	/**
	 * @var Yampee_Routing_Router
	 */
	protected $router;

	/**
	 * @var string
	 */
	protected $rootUrl;

	/**
	 * @var string
	 */
	protected $httpHost;

	/**
	 * @param Yampee_Routing_Router $router
	 * @param Yampee_Kernel         $kernel
	 */
	public function __construct(Yampee_Routing_Router $router, Yampee_Kernel $kernel)
	{
		$this->router = $router;
		$this->rootUrl = $kernel->getLocator()->getRootUrl();
		$this->httpHost = $kernel->getLocator()->getHttpHost();
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'routing';
	}

	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return array(
			new Twig_SimpleFunction('path', array($this, 'generatePath')),
			new Twig_SimpleFunction('url', array($this, 'generateUrl')),
		);
	}

	/**
	 * @param       $routeName
	 * @param array $parameters
	 * @return mixed
	 */
	public function generatePath($routeName, array $parameters = array())
	{
		return $this->rootUrl.$this->router->generate($routeName, $parameters);
	}

	/**
	 * @param       $routeName
	 * @param array $parameters
	 * @param bool  $ssl
	 * @return string
	 */
	public function generateUrl($routeName, array $parameters = array(), $ssl = false)
	{
		$protocol = 'http';

		if ($ssl) {
			$protocol .= 's';
		}

		return $protocol.'://'.$this->httpHost.$this->rootUrl.$this->router->generate($routeName, $parameters);
	}
}