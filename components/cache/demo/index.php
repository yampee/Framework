<?php

require '../autoloader.php';

/*
 * Manager (for one file)
 */
$manager = new Yampee_Cache_Manager(new Yampee_Cache_Storage_Filesystem(dirname(__FILE__).'/cache.temp'));

// You can store strings, integer, booleans, etc.
$manager->set('key1', 'value');
$manager->set('key2', 4);
$manager->set('key3', true);

// But object and arrays too
$manager->set('key4', array('1', '2', '3'));
$manager->set('key5', new stdClass());

// You can set an expiration time (in seconds from now)
$manager->set('key6', 'value', 5); // Expire in 5 seconds
$manager->set('key7', 'value', 24 * 3600); // Expire in 24 hours