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
 * Translator that use a native array to store translations. Slightly slower than GetText.
 */
class Yampee_Translator_Array implements Yampee_Translator_Interface
{
	/**
	 * @var array
	 */
	protected $registry;

	/**
	 * @var string
	 */
	protected $locale;

	/**
	 * @var string
	 */
	protected $domain;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->registry = array();

		$this->setLocale('en')
			->setCurrentDomain('messages');
	}

	/**
	 * Set the current locale
	 *
	 * @param $locale
	 * @return Yampee_Translator_Array
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;

		return $this;
	}

	/**
	 * Translate a message
	 *
	 * @param string $message
	 * @param array  $parameters
	 * @param null   $locale
	 * @param null   $domain
	 * @return mixed|string
	 */
	public function translate($message, array $parameters = array(), $locale = null, $domain = null)
	{
		$replacements = array();

		if (! $locale) {
			$locale = $this->locale;
		}
		if (! $domain) {
			$domain = $this->domain;
		}

		foreach ($parameters as $name => $value) {
			$replacements['%'.$name.'%'] = $value;
		}

		return str_replace(
			array_keys($replacements),
			array_values($replacements),
			$this->registry[$locale][$domain][$message]
		);
	}

	/**
	 * Set the current domain as given domain
	 *
	 * @param string $domain
	 * @return Yampee_Translator_Array
	 */
	public function setCurrentDomain($domain)
	{
		$this->domain = $domain;

		return $this;
	}

	/**
	 * Register a message
	 *
	 * @param string        $message
	 * @param string        $value
	 * @param null|string   $locale
	 * @param null|string   $domain
	 * @return Yampee_Translator_Array
	 */
	public function registerMessage($message, $value, $locale = null, $domain = null)
	{
		if (empty($locale)) {
			$locale = $this->locale;
		}
		if (empty($domain)) {
			$domain = $this->domain;
		}

		if (! isset($this->registry[$locale])) {
			$this->registry[$locale] = array();
		}
		if (! isset($this->registry[$locale][$domain])) {
			$this->registry[$locale][$domain] = array();
		}

		$this->registry[$locale][$domain][$message] = $value;

		return $this;
	}

	/**
	 * Register a list of messages
	 *
	 * @param array         $messages
	 * @param null|string   $locale
	 * @param null|string   $domain
	 * @return Yampee_Translator_Array
	 */
	public function registerMessages(array $messages, $locale = null, $domain = null)
	{
		foreach ($messages as $message => $value) {
			$this->registerMessage($message, $value, $locale, $domain);
		}

		return $this;
	}
}