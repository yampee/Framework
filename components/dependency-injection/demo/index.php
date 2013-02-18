<?php

require '../autoloader.php';

/*
 * Create the container
 */
$container = new Yampee_Di_Container();

/*
 * You can set a service and get it then
 */
$container->set('serviceName', new ServiceObject());
$service = $container->get('serviceName');

/*
 * You can build the container using services and parameters definitions:
 *      %parameterName% is a reference to the parameter called 'parameterName'
 *      @container is a reference to the service called 'container'
 *
 * The container include itself as a service called 'container'
 */
$container->build(
	// Services
	array(
		'container_aware' => array(
			'class' => 'Yampee_Di_ContainerAware',
			'arguments' => array('@container', '%parameterName%')
		)
	),
	// Parameters
	array(
		'parameterName' => 'parameterValue'
	)
);

/*
 * Here 'container_aware' has been built using 'container' service and 'parameterName' parameter as arguments
 */
$container->get('container_aware');

/*
 * The dumper is useful to improve perfomances when the developpement is done
 */
$content = $container->get('container.dumper')->dump($container);