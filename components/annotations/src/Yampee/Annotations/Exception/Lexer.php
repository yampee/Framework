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
 * Lexer exception.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Annotations_Exception_Lexer extends Exception
{
	/**
	 * @var string
	 */
	protected $lexerLine;

	/**
	 * @var integer
	 */
	protected $number;

	/**
	 * @var integer
	 */
	protected $offset;

	/**
	 * Constructor
	 *
	 * @param string    $line
	 * @param int       $number
	 * @param int       $offset
	 */
	public function __construct($line, $number, $offset)
	{
		$this->lexerLine = $line;
		$this->number = $number;
		$this->offset = $offset;

		$this->message = sprintf('Unable to parse element "'.substr($line, $offset).'".');
	}

	/**
	 * @return string
	 */
	public function getLexerLine()
	{
		return $this->lexerLine;
	}

	/**
	 * @return int
	 */
	public function getNumber()
	{
		return $this->number;
	}

	/**
	 * @return int
	 */
	public function getOffset()
	{
		return $this->offset;
	}
}