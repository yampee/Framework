<?php

/*
 * Yampee Framework
 * Open source web development framework for PHP 5.
 *
 * @package Yampee Framework
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Kernel
 */
class Yampee_Kernel
{
	/**
	 * Yampee version
	 */
	const VERSION = '0.1-dev';

	/**
	 * Whether the kernel is in dev or not
	 *
	 * @var boolean
	 */
	protected $inDev;

	/**
	 * Cache manager
	 *
	 * @var Yampee_Cache_Manager
	 */
	protected $cache;

	/**
	 * Configuration
	 *
	 * @var Yampee_Config
	 */
	protected $config;

	/**
	 * Annotation reader
	 *
	 * @var Yampee_Annotations_Reader
	 */
	protected $annotationsReader;

	/**
	 * DI container
	 *
	 * @var Yampee_Di_Container
	 */
	protected $container;

	/**
	 * Yampee locator on the server
	 *
	 * @var Yampee_Locator
	 */
	protected $locator;

	/**
	 * Construct the Kernel
	 *
	 * @param bool $inDev
	 */
	public function __construct($inDev = false)
	{
		set_exception_handler(array('Yampee_ExceptionHandler', 'handle'));
		Yampee_ExceptionHandler::$inDev = $inDev;

		/*
		 * Error reporting
		 *
		 * Different environments will require different levels of error reporting.
		 * By default development will show errors but testing and production will hide them.
		 *
		if($inDev) {
			error_reporting(-1);
			ini_set('display_errors', 1);
		} else {
			error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
			ini_set('display_errors', 0);
		}

		/*
		 * Boot the kernel step by step
		 */
		$this->inDev = $inDev;

		// Cache manager
		$this->cache = new Yampee_Cache_Manager(__APP__.'/app/cache');

		// Annotations reader
		$this->annotationsReader = new Yampee_Annotations_Reader($inDev);

		// Configuration
		$this->config = $this->loadConfig();

		// Container and services
		$this->container = $this->loadContainer();

		$locator = $this->generateRootUrl(Yampee_Http_Request::createFromGlobals());

		$this->container->setParameters($this->config->getArrayCopy());
		$this->container->setParameter('kernel.in_dev', $this->inDev);
		$this->container->setParameter('kernel.root_dir', __APP__);
		$this->container->setParameter('kernel.root_url', $locator->getRootUrl());
		$this->container->setParameter('kernel.document_root', $locator->getDocumentRoot());

		$this->container->set('cache', $this->cache);
		$this->container->set('config', $this->config);
		$this->container->set('annotations', $this->annotationsReader);
		$this->container->set('kernel', $this);

		$this->container->registerDefinitions($this->getCoreDefinitions());

		$this->container->build();

		Yampee_ExceptionHandler::$twig = $this->container->get('twig');
		Yampee_ExceptionHandler::$logger = $this->container->get('logger');

		// Twig Extensions
		$this->loadTwigExtensions();

		// Event dispatcher
		$this->loadEventDispatcher();

		// Load the router and its routes
		$this->loadRouter();

		// Load translations
		$this->loadTranslations();

		$this->container->get('logger')->debug('Kernel loaded');
		$this->container->get('event_dispatcher')->notify('kernel.loaded', array($this->container));
	}

	/**
	 * Handle the request, dispatch the action and return the response
	 *
	 * @param Yampee_Http_Request $request
	 * @return Yampee_Http_RedirectResponse|Yampee_Http_Response
	 * @throws LogicException
	 * @throws Yampee_Http_Exception_NotFound
	 */
	public function handle(Yampee_Http_Request $request)
	{
		$this->container->get('event_dispatcher')->notify('kernel.request', array($request));

		$this->container->get('logger')->debug('Request handled from '.$request->getClientIp());
		$this->container->set('request', $request);

		Yampee_ExceptionHandler::$clientIp = $request->getClientIp();

		$locator = $this->generateRootUrl($request);

		Yampee_ExceptionHandler::$url = $locator->getRequestUri();

		// Redirect without last "/"
		if (substr($locator->getRequestUri(), -1) == '/' && rtrim($locator->getRequestUri(), '/') != '') {
			$this->container->get('logger')->debug(
				'Redirect from '.$locator->getRequestUri().' to '.
					$locator->getRootUrl().rtrim($locator->getRequestUri(), '/')
			);

			return new Yampee_Http_RedirectResponse(
				$locator->getRootUrl().rtrim($locator->getRequestUri(), '/')
			);
		}

		/*
		 * Read Server-Side cache to optimize load
		 */
		$actionsCache = $this->cache->getFile('actions.cache');

		if (! $this->inDev && $actionsCache->has($locator->getRequestUri())) {
			$cache = $actionsCache->get($locator->getRequestUri());

			if ($cache['expire'] < time()) {
				$actionsCache->remove($locator->getRequestUri());
			} else {
				$response = $cache['response'];

				// We clear logs and write a shorter description
				$this->container->get('logger')->clearCurrentScriptLog();
				$this->container->get('logger')->debug(sprintf(
					'%s "%s" handled from %s, loaded from cache',
					$request->getMethod(), $locator->getRequestUri(), $request->getClientIp()
				));

				$this->container->get('logger')->debug('Response sent');

				$this->container->get('event_dispatcher')->notify('kernel.response', array($response));

				return $response;
			}
		}

		// Dispatch the action
		$route = $this->getContainer()->get('router')->find($locator->getRequestUri());

		if(! $route) {
			throw new Yampee_Http_Exception_NotFound(sprintf(
				'No route found for GET %s', $locator->getRequestUri()
			));
		}

		// Call the action
		$this->container->get('logger')->debug('Action found: '.$route->getAction());

		$this->container->get('event_dispatcher')->notify('kernel.action', array($route));

		$action = explode('::', $route->getAction());

		if (! isset($action[1]) || count($action) > 2) {
			throw new LogicException(sprintf(
				'This action is invalid ("%s" given)', $route->getAction()
			));
		}

		$controller = new ReflectionClass($action[0]);
		$controller = $controller->newInstanceArgs(array($this->container));

		$action = new ReflectionMethod($controller, $action[1]);

		$arguments = array();
		$routeAttributes = $route->getAttributes();

		foreach($action->getParameters() as $parameter) {
			if(isset($routeAttributes[$parameter->getName()])) {
				$arguments[] = $routeAttributes[$parameter->getName()];
			}
		}

		$this->container->get('twig')->addGlobal('app', $this->container);

		$response = $action->invokeArgs($controller, $arguments);

		/*
		 * Catch @Template() annotation
		 */
		if (is_array($response)) {
			$responseParameters = $response;
			$response = new Yampee_Http_Response();

			$this->annotationsReader->registerAnnotation(
				new Yampee_Twig_Annotation($response, $responseParameters, $this->getContainer()->get('twig'))
			);

			$this->annotationsReader->readReflector($action);
		}

		if(! $response instanceof Yampee_Http_Response) {
			throw new LogicException(sprintf(
				'Action %s must return a Yampee_Http_Response object (%s given).',
				$route->getAction(), gettype($response)
			));
		}

		/*
		 * Catch @HttpCache() annotation
		 */
		$this->annotationsReader->registerAnnotation(new Yampee_Http_Bridge_Annotation_Cache($response));
		$this->annotationsReader->readReflector($action);

		/*
		 * Catch @Cache() annotation
		 */
		$this->annotationsReader->registerAnnotation(
			new Yampee_Cache_Annotation($this->locator->getRequestUri(), $response, $this->cache->getFile('actions.cache'))
		);
		$this->annotationsReader->readReflector($action);


		// If there is no problem, we clear logs and write a shorter description
		$this->container->get('logger')->clearCurrentScriptLog();
		$this->container->get('logger')->debug(sprintf(
			'%s "%s" handled from %s, calling %s',
			$request->getMethod(), $locator->getRequestUri(), $request->getClientIp(),
			$route->getAction()
		));

		$this->container->get('logger')->debug('Response sent');

		$this->container->get('event_dispatcher')->notify('kernel.response', array($response));

		// Finally send the response
		return $response;
	}

	/**
	 * @return Yampee_Config
	 */
	protected function loadConfig()
	{
		/*
		 * Boot the configuration
		 *
		 * Try to load it from cache if the production mode is enabled
		 */
		$appCache = $this->cache->getFile('app.cache');

		if ($this->inDev || ! $appCache->has('config')) {
			$yaml = new Yampee_Yaml_Yaml();

			$config = new Yampee_Config($yaml->load(__APP__.'/app/config.yml'));
			$config->compile();

			$appCache->set('config', $config);
		} else {
			$config = $appCache->get('config');
		}

		return $config;
	}

	/**
	 * @return Yampee_Di_Container
	 */
	protected function loadContainer()
	{
		/*
		 * Boot the container
		 *
		 * Try to load services list from cache if the production mode is enabled
		 */
		$container = new Yampee_Di_Container();
		$appCache = $this->cache->getFile('app.cache');

		if ($this->inDev || ! $appCache->has('services')) {
			$this->annotationsReader->registerAnnotation(new Yampee_Di_Bridge_Annotation_Service($container));

			// Find all the services files (in src/services) and associate their classes.
			$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
				__APP__.'/src/services'
			));

			$classes = array();

			while($it->valid()) {
				if (! $it->isDot()) {
					$classes[] = trim(str_replace(
						array('\\', '/'), '_',
						$it->getSubPath().'_'.$it->getBasename('.php')
					), '_');
				}

				$it->next();
			}

			// Read annotations on classes (if they exists) and register them in the container
			foreach ($classes as $class) {
				if (class_exists($class)) {
					$this->annotationsReader->readReflector(new ReflectionClass($class));
				}
			}

			$appCache->set('services', $container->getDefinitions());
		} else {
			$container->setDefinitions($appCache->get('services'));
		}

		return $container;
	}

	/**
	 * @return void
	 */
	protected function loadTwigExtensions()
	{
		$this->container->get('twig')->addGlobal('yampee_version', self::VERSION);

		$extensions = $this->container->findByTag('twig.extension');

		foreach ($extensions as $extension) {
			$this->container->get('twig')->addExtension($extension);
		}

		if ($this->inDev) {
			$this->container->get('twig')->addExtension(new Twig_Extension_Debug());
		}
	}

	/**
	 * @return void
	 */
	protected function loadEventDispatcher()
	{
		$listenersNames = $this->container->findNamesByTag('event.listener');

		foreach ($listenersNames as $listenerName) {
			$tags = $this->container->getTags($listenerName);

			foreach ($tags as $tag) {
				$this->container->get('event_dispatcher')->addListener(
					$tag['event'],
					$this->container->get($listenerName),
					$tag['method']
				);
			}
		}
	}

	/**
	 * @return void
	 */
	protected function loadRouter()
	{
		/*
		 * Boot the router
		 *
		 * Try to load routes from cache if the production mode is enabled
		 */
		$router = $this->getContainer()->get('router');
		$appCache = $this->cache->getFile('app.cache');

		$this->annotationsReader->registerAnnotation(new Yampee_Routing_Bridge_Annotation_Route($router));

		if ($this->inDev || ! $appCache->has('routes')) {
			// Find all the controllers files (in src/controllers)
			$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
				__APP__.'/src/controllers'
			));

			$classes = array();

			while($it->valid()) {
				if (! $it->isDot()) {
					$classes[] = trim(str_replace(
						array('\\', '/'), '_',
						$it->getSubPath().'_'.$it->getBasename('.php')
					), '_');
				}

				$it->next();
			}

			$cache = array();

			// Find controllers, actions and routes
			foreach ($classes as $class) {
				if (class_exists($class)) {

					// Prefix
					$reflector = new ReflectionClass($class);
					$classAnnotations = $this->annotationsReader->readReflector($reflector);

					$prefix = '';

					foreach ($classAnnotations as $classAnnotation) {
						if ($classAnnotation instanceof Yampee_Routing_Bridge_Annotation_Route) {
							$prefix = $classAnnotation->pattern;
						}
					}

					// Routes
					$methods = $reflector->getMethods();

					foreach ($methods as $method) {
						if (substr($method->getName(), -6) == 'Action') {
							$annotations = $this->annotationsReader->readReflector($method);

							foreach ($annotations as $annotation) {
								if ($annotation instanceof Yampee_Routing_Bridge_Annotation_Route) {
									$pattern = $prefix.$annotation->pattern;

									if (rtrim($prefix.$annotation->pattern, '/') != '') {
										$pattern = rtrim($pattern, '/');
									}

									$this->container->get('router')->addRoute(new Yampee_Routing_Route(
										$annotation->name,
										$pattern,
										$reflector->getName().'::'.$method->getName(),
										$annotation->defaults,
										$annotation->requirements
									));

									$cache[] = array(
										'name' => $annotation->name,
										'pattern' => $pattern,
										'action' => $reflector->getName().'::'.$method->getName(),
										'defaults' => $annotation->defaults,
										'requirements' => $annotation->requirements
									);
								}
							}
						}
					}
				}
			}

			$appCache->set('routes', $cache);
		} else {
			$routes = $appCache->get('routes');

			foreach ($routes as $route) {
				$this->container->get('router')->addRoute(new Yampee_Routing_Route(
					$route['name'],
					$route['pattern'],
					$route['action'],
					$route['defaults'],
					$route['requirements']
				));
			}
		}
	}

	/**
	 * @return void
	 */
	protected function loadTranslations()
	{
		/*
		 * Boot the translator and load the translations files
		 */
		$translator = $this->container->get('translator');
		$translator->setLocale($this->container->getParameter('framework.locale'));

		$appCache = $this->cache->getFile('app.cache');

		if ($this->inDev || ! $appCache->has('translations')) {
			// Find all the controllers files (in src/controllers)
			$it = new DirectoryIterator(__APP__.'/src/translations');
			$translations = array();

			foreach ($it as $file) {
				if (! $file->isDot() && $file->getExtension() == 'yml') {
					$parts = array_reverse(explode('.', $file->getBasename('.yml')));

					if (count($parts) > 0) {
						$locale = $parts[0];
						unset($parts[0]);
						$domain = implode('.', array_reverse($parts));

						$translations[$domain][$locale] = Yampee_Util_ArrayCompiler::compile(
							(array) $this->container->get('yaml')->load($file->getPathname())
						);
					}
				}

				$it->next();
			}

			$appCache->set('translations', $translations);
		} else {
			$translations = $appCache->get('translations');
		}

		foreach ($translations as $domain => $elements) {
			foreach ($elements as $locale => $messages) {
				foreach ($messages as $key => $message) {
					$translator->registerMessage($key, $message, $locale, $domain);
				}
			}
		}
	}

	/**
	 * @return array
	 */
	protected function getCoreDefinitions()
	{
		/*
		 * Yampee core container definitions
		 */
		return array(
			// Benchmark
			'benchmark' => array(
				'class' => 'Yampee_Benchmark',
				'tags' => array(
					array('name' => 'event.listener', 'event' => 'kernel.loaded', 'method' => 'kernelLoaded'),
					array('name' => 'event.listener', 'event' => 'kernel.request', 'method' => 'kernelRequest'),
					array('name' => 'event.listener', 'event' => 'kernel.action', 'method' => 'kernelAction'),
					array('name' => 'event.listener', 'event' => 'kernel.response', 'method' => 'kernelResponse'),
				)
			),

			// Database
			'database.dsn' => array(
				'class' => 'Yampee_Db_Dsn',
				'arguments' => array(
					'%database.driver%', '%database.database%', '%database.username%',
					'%database.password%', '%database.host%', '%database.port%'
				),
			),
			'database' => array(
				'class' => 'Yampee_Db_Manager',
				'arguments' => array('@database.dsn'),
			),

			// Event dispatcher
			'event_dispatcher' => array(
				'class' => 'Yampee_Ed_Dispatcher',
			),

			// Form factory
			'form_factory' => array(
				'class' => 'Yampee_Form_Factory',
			),

			// Logger
			'logger.default_storage' => array(
				'class' => 'Yampee_Log_Storage_Filesystem',
				'arguments' => array('%framework.log_file%'),
			),
			'logger' => array(
				'class' => 'Yampee_Log_Logger',
				'arguments' => array('@logger.default_storage'),
			),

			// Router
			'router' => array(
				'class' => 'Yampee_Routing_Router',
			),

			// Translator
			'translator' => array(
				'class' => 'Yampee_Translator_Array',
			),

			// Twig
			'twig.loader' => array(
				'class' => 'Twig_Loader_Filesystem',
				'arguments' => array('%twig.views_dir%'),
			),
			'twig' => array(
				'class' => 'Twig_Environment',
				'arguments' => array('@twig.loader', array(
					'debug' => '%twig.debug%',
					'charset' => '%twig.charset%',
					'cache' => '%twig.cache_dir%',
					'strict_variables' => '%twig.strict_variables%',
				)),
			),
			'twig.extensions.core' => array(
				'class' => 'Yampee_Twig_Core',
				'arguments' => array('%kernel.root_url%'),
				'tags' => array(
					array('name' => 'twig.extension')
				)
			),
			'twig.extensions.routing' => array(
				'class' => 'Yampee_Twig_Routing',
				'arguments' => array('@router', '@kernel'),
				'tags' => array(
					array('name' => 'twig.extension')
				)
			),
			'twig.extensions.translation' => array(
				'class' => 'Yampee_Twig_Translation',
				'arguments' => array('@translator'),
				'tags' => array(
					array('name' => 'twig.extension')
				)
			),

			// YAML
			'yaml' => array(
				'class' => 'Yampee_Yaml_Yaml',
			),
		);
	}

	/**
	 * Generate the root URL from the request informations
	 *
	 * @param Yampee_Http_Request $request
	 * @return Yampee_Locator
	 */
	private function generateRootUrl(Yampee_Http_Request $request)
	{
		$rootDir = str_replace('\\', '/', __APP__);
		$scriptName = $request->get('script_name');
		$requestUri = $request->get('request_uri');

		$rootDirParts = explode('/', $rootDir);
		$scriptNameParts = explode('/', $scriptName);
		$scriptNameFirstPart = '';

		foreach($scriptNameParts as $scriptNameFirstPart) {
			if(! empty($scriptNameFirstPart)) {
				break;
			}
		}

		$documentRoot = array();

		foreach($rootDirParts as $rootDirPart) {
			if($rootDirPart != $scriptNameFirstPart) {
				$documentRoot[] = $rootDirPart;
			} else {
				break;
			}
		}

		$documentRoot = implode('/', $documentRoot);
		$rootUrl = '/'.trim(str_replace($documentRoot, '', $rootDir), '/');
		$requestUri = str_replace($rootUrl, '', $requestUri);

		$this->locator = new Yampee_Locator($request->get('http_host'), $documentRoot, $rootUrl, $requestUri);

		return $this->locator;
	}

	/**
	 * @return Yampee_Di_Container
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * @return boolean
	 */
	public function isInDev()
	{
		return $this->inDev;
	}

	/**
	 * @return Yampee_Locator
	 */
	public function getLocator()
	{
		return $this->locator;
	}
}