Using Yampee Annotations
===================

The Annotation class
-----------------------

In Yampee Annotations, annotations are classes to register in the parser.
An annotation has a name, some attributes rules and an execution action:

``` php
<?php

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class RouteAnnotation extends Yampee_Annotations_Definition_Abstract
{
	/**
	 * @var Yampee_Routing_Router
	 */
	protected $router;

	/*
	 * Annotation parameters
	 */
	public $pattern;
	public $name;
	public $defaults;
	public $requirements;

	/**
	 * Return the annotation name: here, we will use the annotation as @Route()
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'Route';
	}

	/**
	 * Return the list of authorized targets.
	 *
	 * An empty array will allow any target.
	 *
	 * @return array
	 */
	public function getTargets()
	{
		return array(self::TARGET_METHOD);
	}

	/**
	 * Return the attributes rules.
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function getAttributesRules()
	{
		$rootNode = new Yampee_Annotations_Definition_RootNode();

		$rootNode
			->anonymousAttr(0, 'pattern', true)
			->stringAttr('name', false)
			->arrayAttr('defaults', false)
				->catchAll()
			->end()
			->arrayAttr('requirements', false)
				->catchAll()
			->end();

		return $rootNode;
	}
}
```

Here, we have the annotation definition for `@Route`, which understand four attributes:
	- an anonymous attribute (no name in the annotation), called then `pattern`
	- a string attribute called `name`
	- an array of anything called `defaults`
	- an array of anything called `requirements`

The boolean parameters the attribute requirement (nedded or not). Here, only the pattern
is required.

Moreover, we target only methods (using `getTargets()`). The available targets are:
`self::TARGET_CLASS, self::TARGET_PROPERTY, self::TARGET_METHOD, self::TARGET_FUNCTION`.
If you return an empty array, the parser will use the annotation for each target.

Thus we catch this:

``` php
<?php

class Controller
{
	/**
	 * @Route('pattern')
	 * @Route('pattern', name = 'homepage')
	 * @Route('pattern', defaults = {}, name = 'homepage')
	 * @Route('pattern', defaults = {}, requirements = {key = 'value'}, name = 'homepage')
	 */
	public function action()
	{
	}
}
```

Once created, you need to register your annotation class:

``` php
<?php
$reader = new Yampee_Annotations_Reader();
$reader->registerAnnotation(new RouteAnnotation());
```

And now, to read annotations on an element, use the `read()` method:

``` php
<?php
class TestController
{
	/**
	 * @Route('/{page}', name = 'homepage', defaults = {})
	 */
	public function action()
	{
	}
}

$reader = new Yampee_Annotations_Reader();
$reader->registerAnnotation(new RouteAnnotation());

$annotations = $reader->read(array('TestController', 'action'));
```

We get annotations on the method. The return value is an array of all annotations,
(the annotations classes are filled with their attributes). In that case, we will access
to attributes using the `RouteAnnotation` class:

``` php
<?php
class TestController
{
	/**
	 * @Route('/{page}', name = 'homepage', defaults = {})
	 */
	public function action()
	{
	}
}

$reader = new Yampee_Annotations_Reader();
$reader->registerAnnotation(new RouteAnnotation());

$annotations = $reader->read(array('TestController', 'action'));

$route = $annotations[0];
$route->pattern;
$route->name;
$route->defaults;
$route->requirements;
```

