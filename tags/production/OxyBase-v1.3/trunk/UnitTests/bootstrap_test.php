<?php
/*
 * Start output buffering
 */
//ob_start();

/*
 * Set error reporting to the level to which code must comply.
 */
error_reporting( E_ALL | E_STRICT );

/*
 * Testing environment
 */
if(!defined('APPLICATION_ENV'))
{
	define('APPLICATION_ENV', 2);
	define('CLIENT', true);
}

if(!defined('BASE_APP_PATH'))
{
	define('BASE_APP_PATH', '../application/');
}

if(!defined('BASE_PATH'))
{
	define('BASE_PATH', './');
}

/*
 * Set default timezone
 */
date_default_timezone_set('GMT');

set_include_path(BASE_APP_PATH . 'library/'       . PATH_SEPARATOR .
                 BASE_APP_PATH . PATH_SEPARATOR .
                 BASE_APP_PATH . 'modules/'       . PATH_SEPARATOR .
                 BASE_APP_PATH . 'admin/modules/' . PATH_SEPARATOR .
                 BASE_APP_PATH . 'admin/'         . PATH_SEPARATOR .
                 BASE_PATH  . PATH_SEPARATOR .

 //Db objects

				 BASE_APP_PATH . 'db_objects/' . PATH_SEPARATOR .

				 BASE_APP_PATH . 'db_objects/Generated/' . PATH_SEPARATOR .
 // Modules
				 BASE_APP_PATH . 'admin/modules/users/models' . PATH_SEPARATOR .

				 BASE_APP_PATH . 'admin/modules/translate/models' . PATH_SEPARATOR .

				 BASE_APP_PATH . 'admin/modules/languages/models' . PATH_SEPARATOR .

				 BASE_APP_PATH . 'admin/modules/currency/models' . PATH_SEPARATOR .

                 BASE_APP_PATH . 'admin/modules/articles/models' . PATH_SEPARATOR .

                 BASE_APP_PATH . 'admin/modules/catalog/models' . PATH_SEPARATOR .

				 BASE_APP_PATH . 'admin/modules/items/models' . PATH_SEPARATOR .

                 BASE_APP_PATH . 'admin/modules/mapping/models' . PATH_SEPARATOR .

                 BASE_APP_PATH . 'admin/modules/collection/models' . PATH_SEPARATOR .

				 BASE_APP_PATH . 'modules/users/models'       . PATH_SEPARATOR .

				 BASE_APP_PATH . 'modules/catalog/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/collection/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/wantlist/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/bookmarks/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/articles/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/offer/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/auction/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/payments/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/banners/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/info/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/currency/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/released/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/pdf/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/currency/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/language/models'       . PATH_SEPARATOR .

                 BASE_APP_PATH . 'modules/released/models'       . PATH_SEPARATOR .

				 BASE_APP_PATH . 'modules/catalog/views/helpers'       . PATH_SEPARATOR .

 // END Modules

                 get_include_path());
require_once 'Zend/Loader.php';

//require_once('Doctrine.compiled.php');

// Autoloading
Zend_Loader::registerAutoload();
Zend_Loader::registerAutoload('Doctrine');


/**
 * Store application root in registry
 */
Zend_Registry::set('testRoot', BASE_APP_PATH);
Zend_Registry::set('testBootstrap', 'bootstrap_test.php');

$obj_front = Zend_Controller_Front::getInstance();
$obj_front->throwExceptions(true);
require 'runtime/LoadErrorHandler.php';
require 'runtime/LoadConfig.php';
require 'runtime/LoadLogger.php';
require 'runtime/LoadDoctrine.php';
require 'runtime/LoadAcl.php';
require 'runtime/LoadModules.php';
require 'runtime/LoadPlugins.php';
require 'runtime/LoadView.php';