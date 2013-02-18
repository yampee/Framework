<?php

require '../../loader/src/Yampee/Loader/Universal.php';

$loader = new Yampee_Loader_Universal();

$loader->registerPrefix(array(
	'Yampee' => array('../src', '../../annotations/src'),
));

/**
 * @Service('test', arguments = {'@container'}, tags = {'twig.extension'})
 */
class TestAnnoted
{
	public function __construct()
	{
		$this->args = func_get_args();
	}
}

$container = new Yampee_Di_Container();

$reader = new Yampee_Annotations_Reader();
$reader->registerAnnotation(new Yampee_Di_Bridge_Annotation_Service($container));
$reader->read('TestAnnoted');

$container->build();

var_dump($container);
