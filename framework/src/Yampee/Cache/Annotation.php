<?php

/*
 * Yampee Components
 * Open source web development components for PHP 5.
 *
 * @package Yampee Components
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Server-Side cache annotation
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Cache_Annotation extends Yampee_Annotations_Definition_Abstract
{
	/**
	 * @var string
	 */
	protected $requestUri;

	/**
	 * @var Yampee_Http_Response
	 */
	protected $response;

	/**
	 * @var Yampee_Cache_Manager
	 */
	protected $cacheWriter;

	/*
	 * Annotation parameters
	 */
	public $expire;

	/**
	 * Constructor
	 *
	 * @param                      $requestUri
	 * @param Yampee_Http_Response $response
	 * @param Yampee_Cache_File    $cacheFile
	 */
	public function __construct($requestUri, Yampee_Http_Response $response, Yampee_Cache_File $cacheFile)
	{
		$this->requestUri = $requestUri;
		$this->response = $response;
		$this->cacheWriter = $cacheFile;
	}

	/**
	 * Execute an action when the annotation is matched.
	 *
	 * @param Reflector $reflector
	 * @return mixed
	 */
	public function execute(Reflector $reflector)
	{
		$this->cacheWriter->set($this->requestUri, array(
			'expire' => time() + $this->expire,
			'response' => $this->response
		));

		$this->cacheWriter->close();
	}

	/**
	 * Define the annotation name.
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'Cache';
	}

	/**
	 * Define the annotation attributes rules (types and if they are required or not).
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function getAttributesRules()
	{
		$rootNode = new Yampee_Annotations_Definition_RootNode();
		$rootNode->numericAttr('expire', true);

		return $rootNode;
	}

	/**
	 * Define the allowed annotation targets. If targets are not provided, all target will be accepted.
	 *
	 * @return array
	 */
	public function getTargets()
	{
		return array(self::TARGET_METHOD);
	}
}