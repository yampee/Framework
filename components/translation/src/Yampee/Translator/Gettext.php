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
 * Translator that use GetText to store translations. Slightly faster than Array.
 */
class Yampee_Translator_Gettext implements Yampee_Translator_Interface
{
	/**
	 * @var string
	 */
	protected $domain;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->setLocale('en_EN')
			->setCurrentDomain('messages');
	}

	/**
	 * Set the current locale
	 *
	 * @param $locale
	 * @return Yampee_Translator_Gettext
	 */
	public function setLocale($locale)
	{
		putenv('LC_ALL='.$locale);
		setlocale(LC_ALL, $locale);

		return $this;
	}

	/**
	 * Translate a message
	 *
	 * @param string $message
	 * @param array  $parameters
	 * @return string
	 */
	public function translate($message, array $parameters = array())
	{
		return gettext($message);
	}

	/**
	 * Set the root path to find translations in for the diven domain
	 * Set the current domain as given domain
	 *
	 * @param string        $path
	 * @param string|null   $domain
	 * @return Yampee_Translator_Gettext
	 */
	public function usePath($path, $domain = null)
	{
		if (empty($domain)) {
			$domain = $this->domain;
		}

		$this->setCurrentDomain($domain);

		bindtextdomain($domain, $path);

		return $this;
	}

	/**
	 * Set the current domain as given domain
	 *
	 * @param string $domain
	 * @return Yampee_Translator_Gettext
	 */
	public function setCurrentDomain($domain)
	{
		$this->domain = $domain;
		textdomain($domain);

		return $this;
	}
}