<?php

require '../autoloader.php';

$factory = new Yampee_Form_Factory();

$form = $factory->createFormBuilder()
	->add('alnum_length')
		->addValidator(new Yampee_Form_Validator_Alnum())
		->addValidator(new Yampee_Form_Validator_ExactLength(4))
		->addFilter(new Yampee_Form_Filter_Xss())
	->end()
	->add('test2')
		->setRequired(false)
		->addFilter(new Yampee_Form_Filter_Xss())
	->end();

$form->bind(array(
	'alnum_length' => 'test'
));

var_dump($form->getData());
var_dump($form->getErrors());