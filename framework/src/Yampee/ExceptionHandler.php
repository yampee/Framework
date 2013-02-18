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
 * Exceptions handler
 */
class Yampee_ExceptionHandler
{
	/**
	 * @var boolean
	 */
	public static $inDev = false;

	/**
	 * @var Twig_Environment
	 */
	public static $twig = null;

	/**
	 * @var Yampee_Log_Logger
	 */
	public static $logger = null;

	/**
	 * @var string
	 */
	public static $url = '(/!\)';

	/**
	 * @var string
	 */
	public static $clientIp = '(/!\)';

	/**
	 * @param Exception $exception
	 */
	public static function handle(Exception $exception)
	{
		$response = new Yampee_Http_Response();

		$msg = $exception->getMessage();

		$exception->class = get_class($exception);
		$exception->hasMessage = ! empty($msg);
		$exception->stack = explode("\n", $exception->getTraceAsString());

		foreach ($exception->stack as $key => $line) {
			preg_match('#\#([0-9]+) (.+)#i', $line, $match);

			if (isset($match[2])) {
				$exception->stack[$key] = $match[2];
			}
		}

		if (self::$logger) {
			$logger = self::$logger;

			if ($exception instanceof Yampee_Http_Exception_General) {
				$error = 'HTTP error '.$exception->getStatusCode();

				if ($exception->getMessage() != '') {
					$error .= ' ('.$exception->getMessage().')';
				}

				$error .= ' thrown by page "'.self::$url.'", requested by '.self::$clientIp;
			} else {
				$error = get_class($exception).' exception';

				if ($exception->getMessage() != '') {
					$error .= ' ('.$exception->getMessage().')';
				}

				$error .= ' thrown by page "'.self::$url.'", requested by '.self::$clientIp;
			}

			$logger->error($error);
			$logger->debug('Response sent');

			$log = $logger->getCurrentScriptLog();

			foreach ($log as $key => $line) {
				preg_match('#\[([a-z]+)\]#i', $line, $match);

				if (isset($match[1])) {
					$type = $match[1];
				} else {
					$type = 'debug';
				}

				$log[$key] = array(
					'type' => strtolower($type),
					'text' => $line
				);
			}

			$exception->log = $log;
		}

		if (self::$inDev) {
			$twig = new Twig_Environment(new Twig_Loader_Filesystem(
				__APP__.'/vendor/yampee/framework/views'
			));

			$file = 'error.html.twig';

			if ($exception instanceof Yampee_Http_Exception_General) {
				if (in_array($exception->getStatusCode(), array(404, 403, 500))) {
					$file = 'error'.$exception->getStatusCode().'.html.twig';
				}
			}

			$response->setContent($twig->render($file, array(
				'exception' => $exception
			)));

			$response->send();
			exit;
		} else {
			$twig = self::$twig;

			if ($exception instanceof Yampee_Http_Exception_General) {
				$response->setStatusCode($exception->getStatusCode());

				if ($twig instanceof Twig_Environment) {
					if (file_exists(__APP__.'/app/views/error'.$exception->getStatusCode().'.html.twig')) {
						$response->setContent($twig->render(
							'error'.$exception->getStatusCode().'.html.twig',
							array('exception' => $exception)
						));

						$response->send();
						exit;
					} elseif (file_exists(__APP__.'/app/views/error.html.twig')) {
						$response->setContent($twig->render(
							'error.html.twig',
							array('exception' => $exception)
						));

						$response->send();
						exit;
					}
				}
			}

			$response->setContent('
					<!DOCTYPE html>
					<html>
						<head>
							<meta charset="UTF-8" />
							<title>An error occured</title>
						</head>
						<body>
							An error occured. Please contact the administrator.
							Sorry for the inconvienience.
						</body>
					</html>
				');

			$response->send();
			exit;
		}

		exit;
	}
}