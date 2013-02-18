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
 * HTTP exception
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Http_Exception_General extends RuntimeException
{
	/**
	 * @var string
	 */
	private $statusCode;

	/**
	 * @var array
	 */
	private $headers;

	/**
	 * Constructor
	 *
	 * @param string    $statusCode
	 * @param null      $message
	 * @param Exception $previous
	 * @param array     $headers
	 * @param int       $code
	 */
	public function __construct($statusCode, $message = null, Exception $previous = null, array $headers = array(), $code = 0)
	{
		$this->statusCode = $statusCode;
		$this->headers = $headers;

		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return string
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}
}
