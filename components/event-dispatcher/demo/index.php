<?php

require '../autoloader.php';

$dispatcher = new Yampee_Ed_Dispatcher();

class Test {
	public function eventNotified($element) {
		return $element.'a';
	}
	public function eventNotified2($element) {
		var_dump($element);
	}
}

$dispatcher->addListener('test', new Test(), 'eventNotified');
$dispatcher->addListener('test', new Test(), 'eventNotified2');

$dispatcher->notify('test', array('test' => 'element'));