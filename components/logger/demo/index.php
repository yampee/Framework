<?php

require '../autoloader.php';

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