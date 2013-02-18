<?php

/*
 * Yampee Framework
 * Open source web development framework for PHP 5.
 *
 * @package Yampee Framework
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

class Yampee_Twig_Translation extends Twig_Extension
{
	/**
	 * @var Yampee_Translator_Interface
	 */
	protected $translator;

	/**
	 * @param Yampee_Translator_Interface $translator
	 */
	public function __construct(Yampee_Translator_Interface $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * @return string
	 */
	public function getFilters()
	{
		return array(
			new Twig_SimpleFilter('trans', array($this, 'translate')),
			new Twig_SimpleFilter('translate', array($this, 'translate')),
		);
	}

	/**
	 * @param       $message
	 * @param array $parameters
	 * @param null  $locale
	 * @param null  $domain
	 * @return string
	 */
	public function translate($message, array $parameters = array(), $locale = null, $domain = null)
	{
		return $this->translator->translate($message, $parameters, $locale, $domain);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'yampee_translation';
	}
}