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
 * Extends the base node to define a root node.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Annotations_Definition_RootNode extends Yampee_Annotations_Definition_Node
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct('root', true, null);
	}
}