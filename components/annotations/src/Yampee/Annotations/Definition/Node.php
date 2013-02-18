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
 * Attributes definition node. Use it to rule your attributes :
 *
 *		public function getAttributesRules()
 *		{
 *			$rootNode = new Yampee_Annotations_Definition_RootNode();
 *
 *			$rootNode
 *				->anonymousAttr(0, 'pattern', true)
 *				->stringAttr('name', false)
 *				->arrayAttr('defaults', false)
 *				    ->catchAll()
 *				->end()
 *				->arrayAttr('requirements', false)
 *				    ->catchAll()
 *				->end();
 *
 *			return $rootNode;
 *		}
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Annotations_Definition_Node
{
	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var boolean
	 */
	protected $required = true;

	/**
	 * @var null|Yampee_Annotations_Definition_Node
	 */
	protected $parent = null;

	/**
	 * @var boolean
	 */
	protected $catchAll = false;

	/**
	 * @var array
	 */
	protected $children = array();

	/**
	 * Constructor
	 *
	 * @param      $name
	 * @param bool $required
	 * @param null $parent
	 */
	public function __construct($name, $required = true, $parent = null)
	{
		if ($parent instanceof self) {
			$this->parent = $parent;
		}

		$this->name = $name;
		$this->required = $required;
		$this->children = array();
	}

	/**
	 * Define a numeric attribute (integer or double).
	 *
	 * @param      $name
	 * @param bool $required
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function numericAttr($name, $required = true)
	{
		$this->children[$name] = array('type' => 'numeric', 'required' => $required);

		if ($required && $this->parent instanceof Yampee_Annotations_Definition_Node) {
			$this->parent->setRequired(true);
		}

		return $this;
	}

	/**
	 * Define a boolean attribute (true or false).
	 *
	 * @param      $name
	 * @param bool $required
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function booleanAttr($name, $required = true)
	{
		$this->children[$name] = array('type' => 'boolean', 'required' => $required);

		if ($required && $this->parent instanceof Yampee_Annotations_Definition_Node) {
			$this->parent->setRequired(true);
		}

		return $this;
	}

	/**
	 * Define a string attribute.
	 *
	 * @param      $name
	 * @param bool $required
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function stringAttr($name, $required = true)
	{
		$this->children[$name] = array('type' => 'string', 'required' => $required);

		if ($required && $this->parent instanceof Yampee_Annotations_Definition_Node) {
			$this->parent->setRequired(true);
		}

		return $this;
	}

	/**
	 * Define an anonymous attribute (an attribute that match anything and don't require name).
	 * The geiven name is the alias for the property in the annotation class.
	 *
	 * @param      $position
	 * @param      $name
	 * @param bool $required
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function anonymousAttr($position, $name, $required = true)
	{
		$this->children[$position] = array('type' => 'any', 'required' => $required, 'name' => $name);

		if ($required && $this->parent instanceof Yampee_Annotations_Definition_Node) {
			$this->parent->setRequired(true);
		}

		return $this;
	}

	/**
	 * Define an array attribute. Restart a node ruling in this array.
	 *
	 * @param      $name
	 * @param bool $required
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function arrayAttr($name, $required = true)
	{
		$this->children[$name] = new self($name, $required, $this);

		if ($required && $this->parent instanceof Yampee_Annotations_Definition_Node) {
			$this->parent->setRequired(true);
		}

		return $this->children[$name];
	}

	/**
	 * Set the current node as catching any kind of datas.
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function catchAll()
	{
		$this->catchAll = true;
		return $this;
	}

	/**
	 * End the current node and return its parent.
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function end()
	{
		if (! $this->parent) {
			return $this;
		}

		return $this->parent;
	}

	/**
	 * Check if the node contains a rule for a child called $name.
	 *
	 * @param $name
	 * @return bool
	 */
	public function has($name)
	{
		return isset($this->children[$name]);
	}

	/**
	 * Check if the node child called $name is required.
	 *
	 * @param $name
	 * @return boolean
	 */
	public function isRequired($name)
	{
		if (! $this->has($name)) {
			return false;
		}

		$child = $this->children[$name];

		if ($child instanceof Yampee_Annotations_Definition_Node) {
			return $child->getRequired();
		} else {
			return $child['required'];
		}
	}

	/**
	 * Set the current node as required.
	 *
	 * @param $required
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * Get if the current node is required or not.
	 *
	 * @return boolean
	 */
	public function getRequired()
	{
		return $this->required;
	}

	/**
	 * Get if the current node catch anything or not.
	 *
	 * @return boolean
	 */
	public function getCatchAll()
	{
		return $this->catchAll;
	}

	/**
	 * Get the current node children.
	 *
	 * @return array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Get the current node parent node (or null).
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function getParent()
	{
		return $this->parent;
	}
}