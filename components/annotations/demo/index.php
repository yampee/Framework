<?php

require '../autoloader.php';
require 'RouteAnnotation.php';

class TestController
{
	/**
	 * @Route('/{page}', name = 'homepage', defaults = {})
	 */
	public function action()
	{

	}
}

// Create an instance of the reader
$reader = new Yampee_Annotations_Reader();

// Register an annotation by its class (that extends Yampee_Annotations_Definition_Abstract)
$reader->registerAnnotation(new RouteAnnotation());

// Read annotation on an element, here a method
$annotations = $reader->read(array('TestController', 'action'));

// $annotations is an array containing a list of annotations objects
$firstAnnotation = $annotations[0];

/*
 * $firstAnnotation is an instance of RouteAnnotation. Its properties
 * have the annotation attributes value:
 */
var_dump($firstAnnotation->getPattern()); // string '/{page}' (length=7)
var_dump($firstAnnotation->getName()); // string 'homepage' (length=8)