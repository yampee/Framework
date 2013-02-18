Yampee Routing: a PHP library to dispatch requests to actions
=============================================================

What is Yampee Routing ?
----------------------------

Yampee Routing is a PHP library that dispatch a request URL to an action. Using Yampee Routing,
you are able to create a powerful routing system very quickly.

An example ?

``` php
<?php
$router = new Yampee_Routing_Router();

$router->addRoute(new Yampee_Routing_Route(
	'list', '/{page}/{id}/{slug}', 'actionName',
	array(), array('page' => '\d+', 'id' => '\d+', 'slug' => '.+')
));

$currentRoute = $router->find('/1/1258/article-slug');
$currentAction = $currentRoute->getAction();
```

Documentation
-------------

The documentation is to be found in the `doc/` directory.

About
-------

Yampee Routing is licensed under the MIT license (see LICENSE file).
The Yampee Routing library is developed and maintained by the Titouan Galopin.
