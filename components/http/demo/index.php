<?php

require '../autoloader.php';

/*
$request = Yampee_Http_Request::createFromGlobals();

throw new Yampee_Http_Exception_AccessDenied();
throw new Yampee_Http_Exception_NotFound();
throw new Yampee_Http_Exception_MethodNotAllowed(array('get', 'post'));
throw new Yampee_Http_Exception_General(500);

$response = new Yampee_Http_Response('content', 200, array());
$response->send();
*/



$session = new Yampee_Http_Session();
$session->set('test', 'value');
$session->setFlash('test', 'value');
$session->get('test');
$session->getFlash('test');
$session->has('test');
$session->hasFlash('test');
$session->remove('test');
$session->removeFlash('test');
$session->all();
$session->allFlashes();