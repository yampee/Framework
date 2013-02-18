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
 * Form
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Form_Form
{
	/**
	 * @var string
	 */
	protected $method;

	/**
	 * @var array
	 */
	protected $fields;

	/**
	 * @var array
	 */
	protected $values;

	/**
	 * @var boolean
	 */
	protected $isValid;

	/**
	 * @var array
	 */
	protected $errors;

	/**
	 * Constructor
	 *
	 * @param string $method
	 */
	public function __construct($method = 'post')
	{
		$this->method = $method;
		$this->isValid = false;
	}

	/**
	 * @param      $name
	 * @param bool $required
	 * @return Yampee_Form_Field
	 */
	public function add($name, $required = true)
	{
		$this->fields[$name] = new Yampee_Form_Field($name, $required, $this);

		return $this->fields[$name];
	}

	/**
	 * @param $name
	 * @return boolean
	 */
	public function has($name)
	{
		return isset($this->fields[$name]);
	}

	/**
	 * @param $name
	 * @return Yampee_Form_Field
	 * @throws InvalidArgumentException
	 */
	public function get($name)
	{
		if (! $this->has($name)) {
			throw new InvalidArgumentException(sprintf('Required for field "%s" does not exists', $name));
		}

		return $this->fields[$name];
	}

	/**
	 * Bind a request, an array or an object with the form.
	 *
	 * @param $data
	 * @return Yampee_Form_Form
	 * @throws InvalidArgumentException
	 */
	public function bind($data)
	{
		$values = array();

		if ($data instanceof Yampee_Http_Request) {
			$values = $data->getAttributes();
		} elseif (is_array($data)) {
			$values = $data;
		} elseif (is_object($data)) {
			$values = get_object_vars($data);
		} else {
			throw new InvalidArgumentException(sprintf(
				'Argument 1 passed to Yampee_Form_Form::bind() must be an object, an array or a
				Yampee_Http_Request instance (%s given).', gettype($data)
			));
		}

		$requiredFields = array();

		foreach ($this->fields as $field) {
			if ($field->isRequired()) {
				$requiredFields[$field->getName()] = true;
			}
		}

		foreach ($values as $key => $value) {
			if ($this->has($key)) {
				$field = $this->get($key);

				unset($requiredFields[$key]);

				if ($field->isValid($value)) {
					$this->values[$key] = $field->filter($value);
				} else {
					$this->errors[$key] = $field->getErrors();
				}
			}
		}

		if (! empty($requiredFields)) {
			foreach ($requiredFields as $fieldName) {
				$this->errors[$fieldName] = 'required';
			}
		}

		if (empty($this->errors)) {
			$this->isValid = true;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->values;
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return boolean
	 */
	public function isValid()
	{
		return $this->isValid;
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}
}