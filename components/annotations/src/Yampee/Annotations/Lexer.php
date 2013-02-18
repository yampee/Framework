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
 * Annotations lexer. Transform annotations lines in list of tokens.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Annotations_Lexer
{
	/**
	 * Available tokens for the tokens lexer.
	 * @var array
	 */
	protected $tokens = array(
		'/^(@)/' => 'T_ROOT',
		'/^(\/)/' => 'T_BLOCK',
		'/^(\*)/' => 'T_COMMENT',
		'/^(\s+)/' => 'T_WHITESPACE',
		'/^(=)/' => 'T_SET',
		'/^(,)/' => 'T_COMMA',
		'/^(true)/i' => 'T_BOOL_TRUE',
		'/^(false)/i' => 'T_BOOL_FALSE',
		'/^(null[^a-z0-9_])/i' => 'T_NULL',
		'/^([0-9.]+)/i' => 'T_DOUBLE',
		'/^([0-9]+)/i' => 'T_INT',
		'/^([a-z0-9_.\-]+)/i' => 'T_KEYWORD',
		'/^(\'.+\')/iU' => 'T_STRING',
		'/^(".+")/iU' => 'T_STRING',
		'/^(\(.*\))/isU' => 'T_ARGS',
		'/^(\{.*\})/isU' => 'T_ARRAY',
		'/^(\[.*\])/isU' => 'T_ARRAY_SIMPLE',
		'/^(.+)/iU' => 'T_OTHER',
	);

	/**
	 * Run the token lexer.
	 *
	 * @param $source
	 * @return array
	 * @throws Yampee_Annotations_Exception_Lexer
	 */
	public function run($source) {
		$tokens = array();

		foreach ((array) $source as $number => $line) {
			$offset = 0;

			while ($offset < strlen($line)) {
				$result = $this->match($line, $number, $offset);

				if ($result === false) {
					// Unable to parse the element
					throw new Yampee_Annotations_Exception_Lexer($line, $number, $offset);
				}

				$tokens[] = $result;
				$offset += strlen($result['match']);
			}
		}

		return $tokens;
	}

	/**
	 * Register a new token.
	 *
	 * @param string $pattern
	 * @param string $name
	 */
	public function addToken($pattern, $name)
	{
		$this->tokens[$pattern] = $name;
	}

	/**
	 * Get the list of registered tokens.
	 *
	 * @return array
	 */
	public function getTokens()
	{
		return $this->tokens;
	}

	/**
	 * Match a string with a token.
	 *
	 * @param $line
	 * @param $number
	 * @param $offset
	 * @return array|bool
	 */
	protected function match($line, $number, $offset) {
		$string = substr($line, $offset);

		foreach($this->tokens as $pattern => $name) {
			if(preg_match($pattern, $string, $matches)) {
				return array(
					'match' => $matches[1],
					'token' => $name,
					'line' => $number+1
				);
			}
		}

		return false;
	}
}