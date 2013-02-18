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
 * Yampee_Loader_WinCache implements a "universal" autoloader for PHP 5 cached with WinCache.
 *
 * It is able to load classes that use the PEAR naming convention for classes
 * (http://pear.php.net/).
 *
 * Classes from a sub-hierarchy of PEAR classes can be looked for in a list of
 * locations to ease the vendoring of a sub-set of classes for large projects.
 *
 * Example usage: see demo/index.php.
 */
class Yampee_Loader_WinCache extends Yampee_Loader_Universal
{
	/**
	 * WinCache storage prefix
	 *
	 * @var string
	 */
	protected $wincachePrefix;

	/**
	 * Constructor
	 *
	 * @param array $wincachePrefix
	 * @param array $prefixes
	 * @throws RuntimeException
	 */
	public function __construct($wincachePrefix, array $prefixes = array())
	{
		if (! function_exists('wincache_ucache_add')) {
			throw new RuntimeException('Unable to use Yampee_Loader_WinCache as WinCache is not enabled.');
		}

		parent::__construct($prefixes);

		$this->wincachePrefix = $wincachePrefix;
	}

	/**
	 * Finds a file by class name while caching lookups to WinCache.
	 *
	 * @param string $className
	 * @return bool|mixed|string
	 */
	public function findFile($className)
	{
		if (wincache_ucache_exists($this->wincachePrefix.$className)) {
			$file = wincache_ucache_get($this->wincachePrefix.$className);
		} else {
			wincache_ucache_add($this->wincachePrefix.$className, $file = parent::findFile($className));
		}

		return $file;
	}
}