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
 * Represents a database record.
 */
class Yampee_Db_Record
{
	/**
	 * @param $name
	 * @param $arguments
	 * @return bool|Yampee_Db_Record
	 * @throws InvalidArgumentException
	 * @throws BadMethodCallException
	 */
	public function __call($name, $arguments)
	{
		if (substr($name, 0, 3) == 'get') {
			return $this->get(substr($name, 3));
		} elseif (substr($name, 0, 2) == 'is') {
			$property = $this->toUnderscores(substr($name, 2));

			if (property_exists($this, $property)) {
				return (boolean) $this->$property;
			} else {
				throw new InvalidArgumentException(sprintf(
					'No field found called: %s', $property
				));
			}
		} elseif (substr($name, 0, 3) == 'set') {
			return $this->set(substr($name, 3), $arguments[0]);
		}

		throw new BadMethodCallException(sprintf(
			'Call to undefined method Yampee_Db_Record::%s()', $name
		));
	}

	/**
	 * Set a field value
	 *
	 * @param $field
	 * @param $value
	 * @return Yampee_Db_Record
	 */
	public function set($field, $value)
	{
		$property = $this->toUnderscores($field);
		$this->$property = $value;

		return $this;
	}

	/**
	 * Get a field value
	 *
	 * @param $field
	 * @return Yampee_Db_Record
	 * @throws InvalidArgumentException
	 */
	public function get($field)
	{
		$property = $this->toUnderscores($field);

		if (property_exists($this, $property)) {
			return $this->$property;
		} else {
			throw new InvalidArgumentException(sprintf(
				'No field found called: %s', $property
			));
		}
	}

	public function toArray()
	{
		return get_object_vars($this);
	}

	private function toUnderscores($str)
	{
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}
}