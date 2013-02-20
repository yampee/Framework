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
 * Security context
 */
class Yampee_Security_Context
{
	/**
	 * @var Yampee_Http_Session
	 */
	protected $session;

	/**
	 * @var string
	 */
	protected $defaultLocale;

	/**
	 * @param Yampee_Http_Session $session
	 * @param string              $defaultLocale
	 */
	public function __construct(Yampee_Http_Session $session, $defaultLocale = 'en')
	{
		$this->session = $session;
		$this->defaultLocale = $defaultLocale;
	}

	/**
	 * @return Yampee_Security_UserToken
	 */
	public function getToken()
	{
		if (! $this->session->has('_yampee_user')) {
			$this->session->set('_yampee_user', new Yampee_Security_UserToken(false, $this->defaultLocale));
		}

		return $this->session->get('_yampee_user');
	}

	/**
	 * @param $data
	 * @return Yampee_Security_Context
	 */
	public function connect($data)
	{
		$this->getToken()->connect($data);

		return $this;
	}

	/**
	 * @param $data
	 * @return Yampee_Security_Context
	 */
	public function disconnect($data)
	{
		$this->session->remove('_yampee_user');

		return $this;
	}
}