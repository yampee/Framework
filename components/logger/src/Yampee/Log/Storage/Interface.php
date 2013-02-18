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
 * Interface for log storages systems
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
interface Yampee_Log_Storage_Interface
{
	/**
	 * Store a message
	 *
	 * @param array $messages
	 * @return Yampee_Log_Storage_Interface
	 */
	public function store($messages);
}