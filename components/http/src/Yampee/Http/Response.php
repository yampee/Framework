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
 * HTTP response
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Http_Response
{
	/**
	 * @var Yampee_Http_ParameterBag
	 */
	public $headers;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @var integer
	 */
	protected $statusCode;

	/**
	 * @var string
	 */
	protected $statusText;

	/**
	 * @var string
	 */
	protected $charset;

	/**
	 * @var array
	 */
	public static $statusTexts = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',            // RFC2518
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',          // RFC4918
		208 => 'Already Reported',      // RFC5842
		226 => 'IM Used',               // RFC3229
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',    // RFC-reschke-http-status-308-07
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',                                               // RFC2324
		422 => 'Unprocessable Entity',                                        // RFC4918
		423 => 'Locked',                                                      // RFC4918
		424 => 'Failed Dependency',                                           // RFC4918
		425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
		426 => 'Upgrade Required',                                            // RFC2817
		428 => 'Precondition Required',                                       // RFC6585
		429 => 'Too Many Requests',                                           // RFC6585
		431 => 'Request Header Fields Too Large',                             // RFC6585
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
		507 => 'Insufficient Storage',                                        // RFC4918
		508 => 'Loop Detected',                                               // RFC5842
		510 => 'Not Extended',                                                // RFC2774
		511 => 'Network Authentication Required',                             // RFC6585
	);

	/**
	 * Constructor.
	 *
	 * @param string  $content The response content
	 * @param integer $status  The response status code
	 * @param array   $headers An array of response headers
	 */
	public function __construct($content = '', $status = 200, $headers = array())
	{
		$this->headers = new Yampee_Http_ParameterBag($headers);

		$this->setContent($content);
		$this->setStatusCode($status);
		$this->setVersion('1.1');
		$this->setCharset('UTF-8');

		if(isset(self::$statusTexts[$status])) {
			$this->setStatusText(self::$statusTexts[$status]);
		}
	}

	/**
	 * Sends HTTP headers.
	 *
	 * @return Yampee_Http_Response
	 */
	public function sendHeaders()
	{
		// headers have already been sent by the developer
		if(headers_sent()) {
			return $this;
		}

		// status
		header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText));

		// headers
		foreach($this->headers->all() as $name => $value) {
			header($name.': '.$value, false);
		}

		return $this;
	}

	/**
	 * Sends content for the current web response.
	 *
	 * @return Yampee_Http_Response
	 */
	public function sendContent()
	{
		echo $this->content;

		return $this;
	}

	/**
	 * Sends HTTP headers and content.
	 *
	 * @return Yampee_Http_Response
	 */
	public function send()
	{
		$this->sendHeaders();
		$this->sendContent();

		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		} elseif ('cli' !== PHP_SAPI) {
			// ob_get_level() never returns 0 on some Windows configurations, so if
			// the level is the same two times in a row, the loop should be stopped.
			$previous = null;
			$obStatus = ob_get_status(1);
			while (($level = ob_get_level()) > 0 && $level !== $previous) {
				$previous = $level;
				if ($obStatus[$level - 1] && isset($obStatus[$level - 1]['del']) && $obStatus[$level - 1]['del']) {
					ob_end_flush();
				}
			}
			flush();
		}

		return $this;
	}

	/**
	 * Is response invalid?
	 * @return Boolean
	 */
	public function isInvalid()
	{
		return $this->statusCode < 100 || $this->statusCode >= 600;
	}

	/**
	 * Is response informative?
	 * @return Boolean
	 */
	public function isInformational()
	{
		return $this->statusCode >= 100 && $this->statusCode < 200;
	}

	/**
	 * Is response successful?
	 * @return Boolean
	 */
	public function isSuccessful()
	{
		return $this->statusCode >= 200 && $this->statusCode < 300;
	}

	/**
	 * Is the response a redirect?
	 * @return Boolean
	 */
	public function isRedirection()
	{
		return $this->statusCode >= 300 && $this->statusCode < 400;
	}

	/**
	 * Is there a client error?
	 * @return Boolean
	 */
	public function isClientError()
	{
		return $this->statusCode >= 400 && $this->statusCode < 500;
	}

	/**
	 * Was there a server side error?
	 * @return Boolean
	 */
	public function isServerError()
	{
		return $this->statusCode >= 500 && $this->statusCode < 600;
	}

	/**
	 * Is the response OK?
	 * @return Boolean
	 */
	public function isOk()
	{
		return 200 === $this->statusCode;
	}

	/**
	 * Is the response forbidden?
	 * @return Boolean
	 */
	public function isForbidden()
	{
		return 403 === $this->statusCode;
	}

	/**
	 * Is the response a not found error?
	 * @return Boolean
	 */
	public function isNotFound()
	{
		return 404 === $this->statusCode;
	}

	/**
	 * Is the response a redirect of some form?
	 * @param string $location
	 * @return Boolean
	 */
	public function isRedirect($location = null)
	{
		return in_array($this->statusCode, array(201, 301, 302, 303, 307, 308)) && (null === $location ?: $location == $this->headers->get('Location'));
	}

	/**
	 * Is the response empty?
	 * @return Boolean
	 */
	public function isEmpty()
	{
		return in_array($this->statusCode, array(201, 204, 304));
	}

	/**
	 * @param string $charset
	 */
	public function setCharset($charset)
	{
		$this->charset = $charset;
	}

	/**
	 * @return string
	 */
	public function getCharset()
	{
		return $this->charset;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param Yampee_Http_ParameterBag $headers
	 */
	public function setHeaders($headers)
	{
		$this->headers = $headers;
	}

	/**
	 * @return Yampee_Http_ParameterBag
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @param int $statusCode
	 */
	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
	}

	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * @param string $statusText
	 */
	public function setStatusText($statusText)
	{
		$this->statusText = $statusText;
	}

	/**
	 * @return string
	 */
	public function getStatusText()
	{
		return $this->statusText;
	}

	/**
	 * @param array $statusTexts
	 */
	public static function setStatusTexts($statusTexts)
	{
		self::$statusTexts = $statusTexts;
	}

	/**
	 * @return array
	 */
	public static function getStatusTexts()
	{
		return self::$statusTexts;
	}

	/**
	 * @param string $version
	 */
	public function setVersion($version)
	{
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}
}