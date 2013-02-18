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
 * Depencency Injection dumper: dump classes files into a single file to improve performances.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Yampee_Di_Dumper
{
	/**
	 * @param Yampee_Di_Container $container
	 * @return string
	 */
	public function dump(Yampee_Di_Container $container)
	{
		$services = $container->getAll();
		$content = '<?php';

		foreach ($services as $service) {
			$reflection = new ReflectionClass($service);
			$content .= str_replace('<?php', '', php_strip_whitespace($reflection->getFileName()));
		}

		return $content;
	}
}