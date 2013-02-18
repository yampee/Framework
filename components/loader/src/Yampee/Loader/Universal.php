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
 * Yampee_Loader_Universal implements a "universal" autoloader for PHP 5.
 *
 * It is able to load classes that use the PEAR naming convention for classes
 * (http://pear.php.net/).
 *
 * Classes from a sub-hierarchy of PEAR classes can be looked for in a list of
 * locations to ease the vendoring of a sub-set of classes for large projects.
 *
 * Example usage: see demo/index.php.
 */
class Yampee_Loader_Universal
{
	/**
	 * Prefixes
	 *
	 * @var array
	 */
	private $prefixes;

	/**
	 * Fallbacks
	 *
	 * @var array
	 */
	private $fallbacks;

	/**
	 * Loaded classes
	 *
	 * @var array
	 */
	private $loadedClasses;


	/**
	 * Constructor
	 *
	 * @param array $prefixes
	 */
	public function __construct(array $prefixes = array())
	{
		$this->prefixes = $prefixes;
		$this->fallbacks = array();
		$this->loadedClasses = array();

		$this->register();
	}


	/**
	 * Register a prefix or a list of prefixes
	 *
	 * @param string|array $prefix
	 * @param string|null $directory
	 * @return Yampee_Loader_Universal
	 */
	public function registerPrefix($prefix, $directory = null)
	{
		if(is_array($prefix)) {
			foreach($prefix as $key => $value) {
				if(is_array($value)) {
					foreach($value as $dir) {
						$this->setPrefix($key, $dir);
					}
				}
				else {
					$this->setPrefix($key, $value);
				}
			}
		}
		else {
			$this->setPrefix($prefix, $directory);
		}

		return $this;
	}


	/**
	 * Register a fallback or a list of fallbacks
	 *
	 * @param string|array $directory
	 * @return Yampee_Loader_Universal
	 */
	public function registerFallback($directory)
	{
		$this->fallbacks = array_merge($this->fallbacks, (array) $directory);

		return $this;
	}


	/**
	 * Register the autoloader in the SPL
	 * Automatically called by the constructor
	 *
	 * @return Yampee_Loader_Universal
	 */
	public function register()
	{
		spl_autoload_register(array($this, 'load'));

		return $this;
	}


	/**
	 * Unregister the autoloader from the SPL
	 *
	 * @return Yampee_Loader_Universal
	 */
	public function unregister()
	{
		spl_autoload_unregister(array($this, 'load'));

		return $this;
	}


	/**
	 * Get a list of loaded classes with the autloader
	 *
	 * @return array
	 */
	public function getLoadedClasses()
	{
		return $this->loadedClasses;
	}


	/**
	 * Method to load a class by its name
	 *
	 * @param string $className
	 * @return boolean
	 */
	public function load($className)
	{
		$file = $this->findFile($className);

		if ($file !== false) {
			require $file;
			$this->loadedClasses[$className] = $file;

			return true;
		}

		return false;
	}

	/**
	 * Split the class name by underscores to find the file path
	 *
	 * @param string $className
	 * @return string
	 */
	public function findFile($className)
	{
		foreach($this->prefixes as $prefix => $directories) {
			if(substr($className, 0, strlen($prefix)) == $prefix) {
				foreach($directories as $directory) {
					if(file_exists($directory.'/'.str_replace('_', '/', $className).'.php')) {
						return $directory.'/'.str_replace('_', '/', $className).'.php';
					}
				}
			}
		}

		foreach($this->fallbacks as $directory) {
			if(file_exists($directory.'/'.str_replace('_', '/', $className).'.php')) {
				return $directory.'/'.str_replace('_', '/', $className).'.php';
			}
		}

		return false;
	}


	/**
	 * Add a prefix and its directory
	 *
	 * @param string $prefix
	 * @param string $directory
	 * @return Yampee_Loader_Universal
	 */
	private function setPrefix($prefix, $directory)
	{
		if(! isset($this->prefixes[$prefix]))
			$this->prefixes[(string) $prefix] = array();

		$this->prefixes[(string) $prefix][] = (string) $directory;

		return $this;
	}
}