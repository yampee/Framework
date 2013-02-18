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
 * HTTP request
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Http_Request
{
	/**
	 * @var array $post
	 */
	private $post;

	/**
	 * @var array $post
	 */
	private $get;

	/**
	 * @var array $post
	 */
	private $server;

	/**
	 * @param $get
	 * @param $post
	 * @param $server
	 */
	public function __construct(array $get, array $post, array $server)
	{
		$this->get = $get;
		$this->post = $post;
		$this->server = $server;
	}

	/**
	 * @return Yampee_Http_Request
	 */
	public static function createFromGlobals()
	{
		return new self($_GET, $_POST, $_SERVER);
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return array_merge($this->get, $this->post);
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get($key)
	{
		$attributes = array();
		$all = array_merge($this->get, $this->post, $this->server);

		foreach ($all as $attrKey => $attrValue) {
			$attributes[strtolower($attrKey)] = $attrValue;
		}

		return (isset($attributes[strtolower($key)])) ? $attributes[strtolower($key)] : false;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function getPost($key)
	{
		$attributes = array();

		foreach ($this->post as $attrKey => $attrValue) {
			$attributes[strtolower($attrKey)] = $attrValue;
		}

		return (isset($attributes[strtolower($key)])) ? $attributes[strtolower($key)] : false;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function getGet($key)
	{
		$attributes = array();

		foreach($this->get as $attrKey => $attrValue) {
			$attributes[strtolower($attrKey)] = $attrValue;
		}

		return (isset($attributes[strtolower($key)])) ? $attributes[strtolower($key)] : false;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function getServer($key)
	{
		$attributes = array();

		foreach($this->server as $attrKey => $attrValue) {
			$attributes[strtolower($attrKey)] = $attrValue;
		}

		return (isset($attributes[strtolower($key)])) ? $attributes[strtolower($key)] : false;
	}

	/**
	 * @return array
	 */
	public function getAllPost()
	{
		return $this->post;
	}

	/**
	 * @return array
	 */
	public function getAllGet()
	{
		return $this->get;
	}

	/**
	 * @return array
	 */
	public function getAllServer()
	{
		return $this->server;
	}

	/**
	 * Get method
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $this->get('request_method');
	}

	/**
	 * Get the current client IP
	 *
	 * @return string
	 */
	public function getClientIp()
	{
		$clientIp = $this->get('http_client_ip');
		$forwardedIp = $this->get('http_x_forwarded_for');
		$remoteAddr = $this->get('remote_addr');

		if (! empty($clientIp)) {
			return $clientIp;
		}

		if (! empty($forwardedIp)) {
			return $forwardedIp;
		}

		return $remoteAddr;
	}
}