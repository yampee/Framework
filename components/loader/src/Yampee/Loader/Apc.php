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
 * Yampee_Loader_Apc implements a "universal" autoloader for PHP 5 cached with APC.
 *
 * It is able to load classes that use the PEAR naming convention for classes
 * (http://pear.php.net/).
 *
 * Classes from a sub-hierarchy of PEAR classes can be looked for in a list of
 * locations to ease the vendoring of a sub-set of classes for large projects.
 *
 * Example usage: see demo/index.php.
 */
class Yampee_Loader_Apc extends Yampee_Loader_Universal
{
	/**
	 * APC storage prefix
	 *
	 * @var string
	 */
	protected $apcPrefix;

	/**
	 * Constructor
	 *
	 * @param array $apcPrefix
	 * @param array $prefixes
	 * @throws RuntimeException
	 */
	public function __construct($apcPrefix, array $prefixes = array())
	{
		if (! extension_loaded('apc')) {
			throw new RuntimeException('Unable to use Yampee_Loader_Apc as APC is not enabled.');
		}

		parent::__construct($prefixes);

		$this->apcPrefix = $apcPrefix;
	}

	/**
	 * Finds a file by class name while caching lookups to APC.
	 *
	 * @param string $className
	 * @return bool|mixed|string
	 */
	public function findFile($className)
	{
		if (false === $file = apc_fetch($this->apcPrefix.$className)) {
			apc_store($this->apcPrefix.$className, $file = parent::findFile($className));
		}

		return $file;
	}
}