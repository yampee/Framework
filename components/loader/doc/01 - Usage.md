Using Yampee Loader
===================

Basic usage
-----------------------

The Yampee Loader library is a set of few classes.
All the loaders extends Yampee_Loader_Universal, the basic class.

For instance, if you have APC on your server, feel free to use Yampee_Loader_Apc:

``` php
<?php

require 'loader/src/Yampee/Loader/Universal.php';
require 'loader/src/Yampee/Loader/Apc.php';

$loader = new Yampee_Loader_Apcl();

$loader->registerPrefix(array(
	'Yampee' => array('../src', '../../annotations/src'),
	'Twig' => '../twig/lib',
	// ...
));
```

There are at the moment three opcached autolaoders:
	- Yampee_Loader_Apc
	- Yampee_Loader_WinCache
	- Yampee_Loader_Xcache

The usage is the same for each of them: just load the right file
and use it!

The dumper
-----------------------

The dumper is an object to dump loaded classes into a single file,
to improve performances on loading:

``` php
<?php
$dumper = new Yampee_Loader_Dumper();
$fileContent = $dumper->dump($loader->getLoadedClasses());
```

Once this done, you can store `$fileContent` in a file which you will include in
your script before the autoloading: as the autoloader will be less called, your
script will be more quick.
