<?php
// Define path to container directory
defined('CONTAINER_PATH') || define('CONTAINER_PATH', realpath(dirname(__FILE__)));

// Add required directories to include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(CONTAINER_PATH . '/bounded-contexts/'),
    realpath(CONTAINER_PATH . '/library'),
    realpath(CONTAINER_PATH . '/build'),
    get_include_path(),
)));

// Simple autoloading
// Replace namespace seperator (\) with path seperator (/)
// if class exists load otherwise return false
spl_autoload_register('autoload');
function autoload($className)
{
	//var_dump($className);
	$pathToFile = str_replace('\\', '/', $className) . '.php';
	if(!class_exists($className)){
		require_once $pathToFile;
	} else {
		return false;
	}
}
