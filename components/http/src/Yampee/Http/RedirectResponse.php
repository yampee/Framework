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
 * HTTP response to redirect
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Http_RedirectResponse extends Yampee_Http_Response
{
	/**
	 * Constructor.
	 *
	 * @param string  $url     The redirection URL
	 * @param integer $status  The response status code
	 * @param array   $headers An array of response headers
	 */
	public function __construct($url, $status = 301, $headers = array())
	{
		parent::__construct('', $status, $headers);

		$this->headers->set('Location', $url);
	}
}