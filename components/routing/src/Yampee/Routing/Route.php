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
 * Route
 */
class Yampee_Routing_Route
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $pattern;

	/**
	 * @var array
	 */
	private $attributes;

	/**
	 * @var array
	 */
	private $requirements;

	/**
	 * @var string
	 */
	private $action;

	/**
	 * @param string $name
	 * @param string $url
	 * @param string $action
	 * @param array  $defaults
	 * @param array  $requirements
	 */
	public function __construct($name, $url, $action, array $defaults = array(), array $requirements = array())
	{
		$this->name = $name;
		$this->action = $action;
		$this->url = $url;
		$this->attributes = $defaults;
		$this->requirements = $requirements;

		// Generate the route pattern using requirements and callback
		$this->pattern = preg_replace_callback(
			'#\\\{(.+)\\\}#iU',
			array($this, 'replaceRequirementsPatterns'),
			preg_quote($url)
		);

		$this->pattern = '#^'.$this->pattern.'$#';
	}

	/**
	 * @param string $url
	 * @return bool
	 */
	public function match($url)
	{
		if (preg_match($this->pattern, $url, $matched)) {
			foreach ($matched as $key => $match) {
				if (! is_numeric($key)) {
					$this->attributes[$key] = $match;
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * @param array $parameters
	 * @return string
	 */
	public function generate(array $parameters = array())
	{
		$remplacements = array();

		foreach ($parameters as $name => $value) {
			$remplacements['{'.$name.'}'] = $value;
		}

		return str_replace(array_keys($remplacements), array_values($remplacements), $this->url);
	}

	/**
	 * @param string $action
	 * @return Yampee_Routing_Route
	 */
	public function setAction($action)
	{
		$this->action = $action;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * @param array $attributes
	 * @return Yampee_Routing_Route
	 */
	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @param string $name
	 * @return Yampee_Routing_Route
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $pattern
	 * @return Yampee_Routing_Route
	 */
	public function setPattern($pattern)
	{
		$this->pattern = $pattern;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @param array $requirements
	 * @return Yampee_Routing_Route
	 */
	public function setRequirements($requirements)
	{
		$this->requirements = $requirements;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getRequirements()
	{
		return $this->requirements;
	}

	/**
	 * @param string $url
	 * @return Yampee_Routing_Route
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}


	/**
	 * Callback to replace requirements pattern in the route pattern.
	 *
	 * @param array $match
	 * @return string
	 */
	private function replaceRequirementsPatterns(array $match)
	{
		if (array_key_exists($match[1], $this->requirements)) {
			return '(?<'.$match[1].'>'.$this->requirements[$match[1]].')';
		} elseif (array_key_exists($match[1], $this->attributes)) {
			return '(?<'.$match[1].'>[^/]*)';
		}

		return '(?<'.$match[1].'>[^/]+)';
	}
}