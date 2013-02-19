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
 * Form field, to validate.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Form_Field
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var boolean
	 */
	protected $required;

	/**
	 * @var Yampee_Form_Form
	 */
	protected $builder;

	/**
	 * @var array
	 */
	protected $validators;

	/**
	 * @var array
	 */
	protected $errors;

	/**
	 * @var array
	 */
	protected $filters;

	/**
	 * @param                  $name
	 * @param                  $required
	 * @param Yampee_Form_Form $builder
	 */
	public function __construct($name, $required, Yampee_Form_Form $builder)
	{
		$this->name = $name;
		$this->builder = $builder;
		$this->required = $required;
		$this->validators = array();
		$this->errors = array();
		$this->filters = array(
			new Yampee_Form_Filter_Xss()
		);
	}

	/**
	 * @param Yampee_Form_Validator_Abstract $validator
	 * @return Yampee_Form_Field
	 */
	public function addValidator(Yampee_Form_Validator_Abstract $validator)
	{
		$this->validators[] = $validator;

		return $this;
	}

	/**
	 * @param Yampee_Form_Filter_Abstract $filter
	 * @return Yampee_Form_Field
	 */
	public function addFilter(Yampee_Form_Filter_Abstract $filter)
	{
		$this->filters[] = $filter;

		return $this;
	}

	/**
	 * @param $string
	 * @return Yampee_Form_Field
	 * @throws InvalidArgumentException
	 */
	public function rule($string)
	{
		$validators = explode('|', $string);
		$calls = array();

		foreach ($validators as $validator) {
			if (strpos($validator, '(') !== false) {
				$parts = explode('(', $validator);

				$calls[] = array(
					'name' => strtolower($parts[0]),
					'class' => 'Yampee_Form_Validator_'.ucfirst(strtolower($parts[0])),
					'args' => explode(',', trim($parts[1], ')'))
				);
			} else {
				$calls[] = array(
					'name' => strtolower($validator),
					'class' => 'Yampee_Form_Validator_'.ucfirst(strtolower($validator)),
					'args' => array()
				);
			}
		}

		foreach ($calls as $call) {
			if (! class_exists($call['class'])) {
				throw new InvalidArgumentException(sprintf(
					'Validator "%s" does not exists.', $call['name']
				));
			}

			$reflection = new ReflectionClass($call['class']);

			$this->addValidator($reflection->newInstanceArgs($call['args']));
		}

		return $this;
	}

	/**
	 * @param $string
	 * @return Yampee_Form_Field
	 * @throws InvalidArgumentException
	 */
	public function filter($string)
	{
		$filters = explode('|', $string);
		$calls = array();

		foreach ($filters as $filter) {
			if (strpos($filter, '(') !== false) {
				$parts = explode('(', $filter);

				$calls[] = array(
					'name' => strtolower($parts[0]),
					'class' => 'Yampee_Form_Filter_'.ucfirst(strtolower($parts[0])),
					'args' => explode(',', trim($parts[1], ')'))
				);
			} else {
				$calls[] = array(
					'name' => strtolower($filter),
					'class' => 'Yampee_Form_Filter_'.ucfirst(strtolower($filter)),
					'args' => array()
				);
			}
		}

		foreach ($calls as $call) {
			if (! class_exists($call['class'])) {
				throw new InvalidArgumentException(sprintf(
					'Filter "%s" does not exists.', $call['name']
				));
			}

			$reflection = new ReflectionClass($call['class']);

			$this->addFilter($reflection->newInstanceArgs($call['args']));
		}

		return $this;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function isValid($value)
	{
		$errors = array();

		foreach ($this->validators as $validator) {
			if (! $validator->validate($value)) {
				$errors[] = $validator->getMessage();
			}
		}

		$this->errors = $errors;

		return empty($errors);
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function passInFilters($value)
	{
		foreach ($this->filters as $filter) {
			$value = $filter->filter($value);
		}

		return $value;
	}

	/**
	 * @return bool
	 */
	public function isRequired()
	{
		return $this->required;
	}

	/**
	 * @param boolean $required
	 * @return Yampee_Form_Field
	 */
	public function setRequired($required)
	{
		$this->required = $required;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return array
	 */
	public function getValidators()
	{
		return $this->validators;
	}

	/**
	 * @return Yampee_Form_Form
	 */
	public function getBuilder()
	{
		return $this->builder;
	}

	/**
	 * @return array
	 */
	public function getFilters()
	{
		return $this->filters;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return Yampee_Form_Form
	 */
	public function end()
	{
		return $this->builder;
	}
}