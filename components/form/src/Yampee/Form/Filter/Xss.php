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
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Form_Filter_Xss extends Yampee_Form_Filter_Abstract
{
	/**
	 * @var string
	 */
	protected $charset;

	/**
	 * @var array
	 */
	public $htmlspecialcharsCharsets = array(
		'iso-8859-1' => true, 'iso8859-1' => true,
		'iso-8859-15' => true, 'iso8859-15' => true,
		'utf-8' => true,
		'cp866' => true, 'ibm866' => true, '866' => true,
		'cp1251' => true, 'windows-1251' => true, 'win-1251' => true,
		'1251' => true,
		'cp1252' => true, 'windows-1252' => true, '1252' => true,
		'koi8-r' => true, 'koi8-ru' => true, 'koi8r' => true,
		'big5' => true, '950' => true,
		'gb2312' => true, '936' => true,
		'big5-hkscs' => true,
		'shift_jis' => true, 'sjis' => true, '932' => true,
		'euc-jp' => true, 'eucjp' => true,
		'iso8859-5' => true, 'iso-8859-5' => true, 'macroman' => true,
	);

	/**
	 * @param string $charset
	 */
	public function __construct($charset = 'UTF-8')
	{
		$this->charset = $charset;
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function filter($value)
	{
		if (isset($this->htmlspecialcharsCharsets[strtolower($this->charset)])) {
			return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, $this->charset);
		}

		return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8');
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'xss';
	}
}