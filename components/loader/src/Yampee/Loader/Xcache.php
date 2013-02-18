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
 * Yampee_Loader_Xcache implements a "universal" autoloader for PHP 5 cached with Xcache.
 *
 * It is able to load classes that use the PEAR naming convention for classes
 * (http://pear.php.net/).
 *
 * Classes from a sub-hierarchy of PEAR classes can be looked for in a list of
 * locations to ease the vendoring of a sub-set of classes for large projects.
 *
 * Example usage: see demo/index.php.
 */
class Yampee_Loader_Xcache extends Yampee_Loader_Universal
{
	/**
	 * Xcache storage prefix
	 *
	 * @var string
	 */
	protected $xcachePrefix;

	/**
	 * Constructor
	 *
	 * @param array $xcachePrefix
	 * @param array $prefixes
	 * @throws RuntimeException
	 */
	public function __construct($xcachePrefix, array $prefixes = array())
	{
		if (! extension_loaded('Xcache')) {
			throw new RuntimeException('Unable to use Yampee_Loader_Xcache as Xcache is not enabled.');
		}

		parent::__construct($prefixes);

		$this->xcachePrefix = $xcachePrefix;
	}

	/**
	 * Finds a file by class name while caching lookups to Xcache.
	 *
	 * @param string $className
	 * @return bool|mixed|string
	 */
	public function findFile($className)
	{
		if (xcache_isset($this->xcachePrefix.$className)) {
			$file = xcache_get($this->xcachePrefix.$className);
		} else {
			xcache_set($this->xcachePrefix.$className, $file = parent::findFile($className));
		}

		return $file;
	}
}