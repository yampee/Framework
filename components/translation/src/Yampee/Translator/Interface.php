<?php

/*
 * Yampee Components
 * Open source web development components for PHP 5.
 *
 * @package Yampee Components
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

interface Yampee_Translator_Interface
{
	/**
	 * Set the current locale
	 *
	 * @param $locale
	 * @return Yampee_Translator_Interface
	 */
	public function setLocale($locale);

	/**
	 * Translate a message
	 *
	 * @param string $message
	 * @param array  $parameters
	 * @return string
	 */
	public function translate($message, array $parameters = array());
}