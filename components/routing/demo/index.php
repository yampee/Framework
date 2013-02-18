<?php

require '../autoloader.php';

$router = new Yampee_Routing_Router();

$router->addRoute(new Yampee_Routing_Route(
	'list', '/{page}/{id}/{slug}', 'actionName',
	array(), array('page' => '\d+', 'id' => '\d+', 'slug' => '.+')
));

$currentRoute = $router->find('/1/1258/article-slug');
$currentAction = $currentRoute->getAction();
