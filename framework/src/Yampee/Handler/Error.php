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
 * Errors handler
 */
class Yampee_Handler_Error
{
	/**
	 * @var boolean
	 */
	public static $inDev = false;

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
	 * Handle errors.
	 *
	 * @return bool
	 */
	public static function handle()
	{
		$error = error_get_last();

		if (! $error) {
			return true;
		}

		ob_end_clean();

		$string = $error['message'];
		$line = $error['line'];
		$file = $error['file'];

		if (self::$logger) {
			$logger = self::$logger;

			$error = 'Error ';
			$error .= '"'.$string.'" thrown by page "'.self::$url;
			$error .= '" (in file '.$file.' on line '.$line.'), requested by '.self::$clientIp;

			$logger->error($error);
			$logger->debug('Response sent');

			$log = $logger->getCurrentScriptLog();

			foreach ($log as $key => $logLine) {
				preg_match('#\[([a-z]+)\]#i', $logLine, $match);

				if (isset($match[1])) {
					$type = $match[1];
				} else {
					$type = 'debug';
				}

				$log[$key] = array(
					'type' => strtolower($type),
					'text' => $logLine
				);
			}
		}

		if (self::$inDev) {
			$html = '<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />

		<title>{{ error.string }}</title>

		<style>
			html {
				background: #EEEEEE;
				color: #313131;
				font-family: Arial;
				font-size: 13px;
			}
			.container {
				width: 940px;
				margin: 50px auto;
				padding: 20px 28px;
				background: white;
				border: 1px solid #DFDFDF;
				border-radius: 16px 16px 16px 16px;
				margin-bottom: 20px;
			}
			h2, h4 {
				font-weight: lighter;
			}
			h4 {
				font-size: 14px;
				margin-top: 5px;
				margin-left: 20px;
				margin-bottom: 40px;
			}
			strong {
				font-weight: bold;
			}
			p {
				margin: 7px 0;
				padding: 0;
			}
			pre {
				width: 920px;
				overflow: hidden;
			}
			li {
				padding: 5px;
				color: #888;
			}
			.error {
				background: #F9ECEC;
				color: #AA3333;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<h2>{{ error.string }}</h2>

			<h4>in <strong>{{ error.file }}</strong> on line <strong>{{ error.line }}</strong></h4>

			{{ logs }}
		</div>
	</body>
</html>';

			$remplacements = array(
				'{{ error.string }}' => ucfirst($string),
				'{{ error.file }}' => $file,
				'{{ error.line }}' => $line,
			);

			$remplacements['{{ logs }}'] = '';

			if (isset($log)) {
				$remplacements['{{ logs }}'] = '<h3>Logs</h3><ol>';

				foreach ($log as $line) {
					$remplacements['{{ logs }}'] .= '<li class="'.$line['type'].'">'.$line['text'].'</li>';
				}

				$remplacements['{{ logs }}'] .= '</ol>';
			}

			$response = new Yampee_Http_Response(
				str_replace(array_keys($remplacements), array_values($remplacements), $html),
				500
			);
		} else {
			$response = new Yampee_Http_Response('', 500);

			$response->setContent('
					<!DOCTYPE html>
					<html>
						<head>
							<meta charset="UTF-8" />
							<title>Oops, something went wrong with this page!</title>
						</head>
						<body>
							<h1>Oops, something went wrong with this page!</h1>
							<p>
								An error occured. Please contact the administrator.
								Sorry for the inconvienience.
							</p>
						</body>
					</html>
				');
		}

		$response->send();
		exit;
	}
}