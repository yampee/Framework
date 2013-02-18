<?php

require '../src/Yampee/Loader/Universal.php';

$loader = new Yampee_Loader_Universal();

/*
 * The autolaoder will search in those directories to found the class
 */
$loader->registerPrefix(array(
	'Yampee' => array('../src', '../../annotations/src'),
	'Twig' => '../twig/lib',
));

/*
 * If the class is not found with prefixes, the autolaoder will search in the fallbacks
 */
$loader->registerFallback(array(
	'controllers'
));

/*
 * Finally, with the Dumper, you can easily create a file containning
 * all the classes you have loaded, to improve performances.
 */
$dumper = new Yampee_Loader_Dumper();
$fileContent = $dumper->dump($loader->getLoadedClasses());

/*
 * With the same usage, you can use easily some accelerators as
 * APC, Xcache or WinCache:
 */
$loader = new Yampee_Loader_Apc('apc_prefix');
$loader = new Yampee_Loader_Xcache('xcache_prefix');
$loader = new Yampee_Loader_WinCache('win_cache_prefix');