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
 * Annotations parser. Parse a DocBlock comment to find annotations and their attributes.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Annotations_Parser
{
	/**
	 * @var array Annotations names to ignore during parsing.
	 */
	private $ignored = array(
		'access', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link', 'see', 'since',
		'tutorial', 'version', 'package', 'subpackage', 'name', 'global', 'param', 'return', 'staticvar', 'category',
		'staticVar', 'static', 'var', 'throws', 'inheritdoc', 'inheritDoc', 'license', 'todo', 'deprecated', 'deprec',
		'author', 'property', 'method', 'abstract', 'exception', 'magic', 'api', 'final', 'filesource', 'throw', 'uses',
		'usedby', 'private', 'override', 'codeCoverageIgnore', 'codeCoverageIgnoreStart', 'codeCoverageIgnoreEnd',
		'Required', 'Attribute', 'Attributes', 'Target', 'SuppressWarnings', 'ingroup', 'code', 'endcode',
		'package_version', 'Annotation'
	);

	/**
	 * @var Yampee_Annotations_Lexer
	 */
	private $lexer;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->lexer = new Yampee_Annotations_Lexer();
	}

	/**
	 * Parse a doc comment and return an array of annotations on it.
	 *
	 * @param $docComment
	 * @return array
	 */
	public function parse($docComment)
	{
		$tokens = $this->lexer->run($docComment);

		$annotations = array();
		$annotation = array();

		foreach ($tokens as $token) {
			if ($token['token'] == 'T_ROOT' || $token['token'] == 'T_BLOCK') {
				if (isset($annotation['name']) && ! in_array($annotation['name'], $this->ignored)) {
					$annotations[$annotation['name']][] = $annotation;
				}

				$annotation = array('attributes' => array());
			} elseif ($token['token'] == 'T_KEYWORD' && ! isset($annotation['name'])) {
				$annotation['name'] = $token['match'];
			} elseif ($token['token'] == 'T_ARGS') {
				$annotation['attributes'] = $this->parseArguments(trim((string) $token['match'], '()'));
			}
		}

		return $annotations;
	}

	/**
	 * Parse annotation arguments with recursion on arrays.
	 *
	 * @param $arguments
	 * @return array
	 */
	private function parseArguments($arguments)
	{
		$tokens = $this->lexer->run($arguments);

		$arguments = array();

		$currentKey = 0;

		foreach ($tokens as $token) {
			if ($token['token'] == 'T_KEYWORD') {
				$currentKey = $token['match'];
			} elseif ($token['token'] == 'T_BOOL_TRUE') {
				$arguments[$currentKey] = true;
				$currentKey = count($arguments);
			} elseif ($token['token'] == 'T_BOOL_FALSE') {
				$arguments[$currentKey] = false;
				$currentKey = count($arguments);
			} elseif ($token['token'] == 'T_NULL') {
				$arguments[$currentKey] = null;
				$currentKey = count($arguments);
			} elseif ($token['token'] == 'T_INT') {
				$arguments[$currentKey] = (int) $token['match'];
				$currentKey = count($arguments);
			} elseif ($token['token'] == 'T_DOUBLE') {
				$arguments[$currentKey] = (double) $token['match'];
				$currentKey = count($arguments);
			} elseif ($token['token'] == 'T_STRING') {
				$arguments[$currentKey] = trim((string) $token['match'], '\'');
				$currentKey = count($arguments);
			} elseif ($token['token'] == 'T_ARRAY') {
				$arguments[$currentKey] = (array) $this->parseArguments(trim((string) $token['match'], '{}'));
				$currentKey = count($arguments);
			} elseif ($token['token'] == 'T_ARRAY_SIMPLE') {
				$arguments[$currentKey] = (array) $this->parseArguments(trim((string) $token['match'], '[]'));
				$currentKey = count($arguments);
			}
		}

		return $arguments;
	}

	/**
	 * Add an ignored annotation name to avoid errors.
	 *
	 * @param $name
	 * @return Yampee_Annotations_Parser
	 */
	public function addIgnored($name)
	{
		$this->ignored[] = $name;

		return $this;
	}

	/**
	 * Remove an ignored annotation name.
	 *
	 * @param $name
	 * @return Yampee_Annotations_Parser
	 */
	public function removeIgnored($name)
	{
		if ($key = array_search($name, $this->ignored)) {
			unset($this->ignored[$key]);
		}

		return $this;
	}
}