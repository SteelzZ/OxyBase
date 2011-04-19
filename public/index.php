<?php
/*
ini_set('max_execution_time', 0);
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../project/'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

require_once 'Amqp/amqp.inc';
require('symfony/sfServiceContainerAutoloader.php');
sfServiceContainerAutoloader::register();

require_once '../project/build/di/output/ApplicationContainer.php';
$appContainer = new ApplicationContainer();
$app = $appContainer->getService('OxyApplication');
$app->run();
*/
print "hi";