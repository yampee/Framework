Yampee Log: a PHP library to log your script
=============================================================

What is Yampee Log ?
----------------------------

Yampee Log is a PHP library that provides an easy way to log messages from your script on
your server, for instance to debug errors.

An example ?

``` php
<?php
$logger = new Yampee_Log_Logger(new Yampee_Log_Storage_Filesystem(dirname(__FILE__).'/dev.log'));

/*
 * Different kind of messages
 */
$logger->error('Message');
$logger->emergency('Message');
$logger->critical('Message');
$logger->warning('Message');
$logger->alert('Message');
$logger->notice('Message');
$logger->info('Message');
$logger->debug('Message');

/*
 * Return an array containing all the current script log messages
 */
$logger->getCurrentScriptLog();
```

Documentation
-------------

The documentation is to be found in the `doc/` directory.

About
-------

Yampee Log is licensed under the MIT license (see LICENSE file).
The Yampee Log library is developed and maintained by the Titouan Galopin.
