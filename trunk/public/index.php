<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../project/'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));
require('Symfony/sfServiceContainerAutoloader.php');
sfServiceContainerAutoloader::register();

require_once 'Oxy/DependencyInjection/Container.php';
$appContainer = new Oxy_DependencyInjection_Container();
$app = $appContainer->getService('OxyApplication');
$app->run();