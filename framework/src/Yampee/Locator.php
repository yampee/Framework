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
 * Class to store location of Yampee in the server and
 * its root URL.
 */
class Yampee_Locator
{
	/**
	 * @var string
	 */
	protected $httpHost;

	/**
	 * @var string
	 */
	protected $documentRoot;

	/**
	 * @var string
	 */
	protected $rootUrl;

	/**
	 * @var string
	 */
	protected $requestUri;

	/**
	 * @param $httpHost
	 * @param $documentRoot
	 * @param $rootUrl
	 * @param $requestUri
	 */
	public function __construct($httpHost, $documentRoot, $rootUrl, $requestUri)
	{
		$this->httpHost = $httpHost;
		$this->documentRoot = $documentRoot;
		$this->rootUrl = $rootUrl;
		$this->requestUri = $requestUri;
	}

	/**
	 * @return string
	 */
	public function getDocumentRoot()
	{
		return $this->documentRoot;
	}

	/**
	 * @return string
	 */
	public function getRequestUri()
	{
		return $this->requestUri;
	}

	/**
	 * @return string
	 */
	public function getRootUrl()
	{
		return $this->rootUrl;
	}

	/**
	 * @return string
	 */
	public function getHttpHost()
	{
		return $this->httpHost;
	}
}