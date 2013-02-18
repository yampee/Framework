<?php

/*
 * Yampee Framework
 * Open source web development framework for PHP 5.
 *
 * @package Yampee Framework
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

class Yampee_Twig_Annotation extends Yampee_Annotations_Definition_Abstract
{
	/**
	 * @var Yampee_Http_Response
	 */
	protected $response;

	/**
	 * @var array
	 */
	protected $responseParameters;

	/**
	 * @var Twig_Environment
	 */
	protected $twig;

	/*
	 * Annotation parameters
	 */
	public $template;

	/**
	 * Constructor
	 *
	 * @param Yampee_Http_Response $response
	 * @param array                $responseParameters
	 * @param Twig_Environment     $twig
	 */
	public function __construct(Yampee_Http_Response $response, array $responseParameters, Twig_Environment $twig)
	{
		$this->response = $response;
		$this->responseParameters = $responseParameters;
		$this->twig = $twig;
	}

	/**
	 * Execute an action when the annotation is matched.
	 *
	 * @param Reflector $reflector
	 * @return mixed
	 */
	public function execute(Reflector $reflector)
	{
		if (! empty($this->template)) {
			$template = $this->template;
		} else {
			$template = str_replace('Controller', '', $reflector->getDeclaringClass()->getName()).'/';
			$template .= str_replace('Action', '', $reflector->getName());
			$template .= '.html.twig';
		}

		$this->response->setContent($this->twig->render($template, $this->responseParameters));
	}

	/**
	 * Define the annotation name.
	 *
	 * @return string
	 */
	public function getAnnotationName()
	{
		return 'Template';
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
			->anonymousAttr(0, 'template', false)
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