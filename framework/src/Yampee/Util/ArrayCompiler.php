<?php

/*
 * Yampee Framework
 * Open source web development framework for PHP 5.
 *
 * @package Yampee Framework
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Array compiler: an objet to compile complex arrays in dot notations arrays.
 */
class Yampee_Util_ArrayCompiler
{
	/**
	 * Convert collection to 2D collection with dot notation keys
	 *
	 * @param array $array
	 * @return array
	 */
	public static function compile(array $array)
	{
		$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
		$result = array();

		foreach($iterator as $leafValue) {
			$keys = array();

			foreach (range(0, $iterator->getDepth()) as $depth) {
				if (is_numeric($iterator->getSubIterator($depth)->key())) {
					break;
				}

				$keys[] = $iterator->getSubIterator($depth)->key();
			}

			$result[implode('.', $keys)][] = $leafValue;
		}

		foreach ($result as $key => $array) {
			if (count($array) == 1) {
				$result[$key] = $array[0];
			}
		}

		return $result;
	}
}