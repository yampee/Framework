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
 * HTTP Cache-Control annotation
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Http_Bridge_Annotation_Cache extends Yampee_Annotations_Definition_Abstract
{
	/**
	 * @var Yampee_Http_Response
	 */
	protected $response;

	/*
	 * Annotation parameters
	 */
	public $public = false;
	public $maxAge = 300;
	public $mustRevalidate = false;

	/**
	 * Constructor
	 *
	 * @param Yampee_Http_Response $response
	 */
	public function __construct(Yampee_Http_Response $response = null)
	{
		if (empty($response)) {
			$response = new Yampee_Http_Response();
		}

		$this->response = $response;
	}

	/**
	 * Execute an action when the annotation is matched.
	 *
	 * @param Reflector $reflector
	 * @return mixed
	 */
	public function execute(Reflector $reflector)
	{
		$cacheControl = array();

		if ($this->public) {
			$cacheControl[] = 'public';
		} else {
			$cacheControl[] = 'private';
		}

		if (is_int($this->maxAge)) {
			$cacheControl[] = 'max-age='.$this->maxAge;
		}

		if ($this->mustRevalidate) {
			$cacheControl[] = 'must-revalidate';
		}

		$this->response->getHeaders()->set('Cache-Control', implode(', ', $cacheControl));
	}

	/**
	 * Define the annotation name.
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'HttpCache';
	}

	/**
	 * Define the annotation attributes rules (types and if they are required or not).
	 *
	 * @return Yampee_Annotations_Definition_Node
	 */
	public function getAttributesRules()
	{
		$rootNode = new Yampee_Annotations_Definition_RootNode();

		$rootNode
			->booleanAttr('public', false)
			->numericAttr('maxAge', false)
			->booleanAttr('mustRevalidate', false)
		;

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