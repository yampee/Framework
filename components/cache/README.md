Yampee Cache: a PHP library to easily cache datas
=============================================================

What is Yampee Cache ?
----------------------------

Yampee Cache is a PHP library that provides an easy way to store datas on hard drive to retrieve
them later and faster.

An example ?

``` php
<?php
$manager = new Yampee_Cache_Manager(dirname(__FILE__).'/cache.temp');

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
```

Documentation
-------------

The documentation is to be found in the `doc/` directory.

About
-------

Yampee Cache is licensed under the MIT license (see LICENSE file).
The Yampee Cache library is developed and maintained by the Titouan Galopin.
