<?php

require '../autoloader.php';


/*
 * GetText storage usage
 */
$translator = new Yampee_Translator_Gettext();
$translator->usePath(dirname(__FILE__).'/translations');

$translator->translate('hello.world');


/*
 * Native array storage usage
 */
$translator = new Yampee_Translator_Array();

$translator->registerMessage('hello.world', 'Hello World !', 'en');
$translator->registerMessage('hello.world', 'Salut le monde !', 'fr');

// By default, locale is set as 'en'
$translator->translate('hello.world'); // Hello World !

// Set locale as 'fr'
$translator->setLocale('fr');
$translator->translate('hello.world'); // Salut le monde !

// You can register mutliples messages too
$translator->registerMessages(array(
	'hello.world' => 'Salut le monde !',
	'foo.bar' => 'Foo Bar !',
	'bar.foo' => 'Bar Foo !',
), 'fr');

// You can even use parameters (not in GetText)
$translator->registerMessage('hello.world.name', 'Salut %name% !', 'fr');
$translator->translate('hello.world.name', array('name' => 'Titouan'));