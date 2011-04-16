<?php
/***********************************************************************************************************************
 * Bootstrap application
 **********************************************************************************************************************/
ini_set('max_execution_time', 0);
ini_set("soap.wsdl_cache_enabled", "0");
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../project/'));

// Define application environment
define('APPLICATION_ENV', 'api');    
    
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

// Oxy_Application
require_once 'Amqp/amqp.inc';
require('Symfony/sfServiceContainerAutoloader.php');
sfServiceContainerAutoloader::register();

require_once '../project/build/di/output/ApplicationContainer.php';
$di = new ApplicationContainer();

require_once 'Oxy/Application.php';
// Create application and bootstrap
$application = new Oxy_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/config.xml'
);

$app = $application->bootstrap();
$app->bootstrap();

/***********************************************************************************************************************
 * Process request
 **********************************************************************************************************************/
$boundedContext = isset($_GET['bc']) ? $_GET['bc'] : 'oxy-base';
$service = isset($_GET['service']) ? $_GET['service'] : 'account';
$apiType = isset($_GET['api_type']) ? $_GET['api_type'] : 'general';
$version = isset($_GET['version']) ? $_GET['version'] : 'v1r0';
$action = isset($_GET['action']) ? $_GET['action'] : '';

$boundedContextCamelCase = Oxy_Utils_String::dashToCamelCase($boundedContext);
$boundedContextCamelCaseUcFirst = ucfirst($boundedContextCamelCase);
$serviceCamelCase = Oxy_Utils_String::dashToCamelCase($service);
$apiTypeCamelCase = Oxy_Utils_String::dashToCamelCase($apiType);
$versionCamelCase = Oxy_Utils_String::dashToCamelCase($version);

$autoDiscoverUrl = "{$di->getParameter('protocol')}://{$di->getParameter('api.url')}/api/{$boundedContext}/{$service}/{$apiType}/{$version}/";
$wsdlUrl = $autoDiscoverUrl . 'wsdl';

// Build up service class name
$handlerServiceClassName = "{$boundedContextCamelCase}{$serviceCamelCase}{$apiTypeCamelCase}";

$handlerServiceName = "{$boundedContextCamelCase}webService{$serviceCamelCase}{$apiTypeCamelCase}{$versionCamelCase}Service";
$handlerServiceMetaInfoName = "{$boundedContextCamelCase}webService{$serviceCamelCase}{$apiTypeCamelCase}{$versionCamelCase}ServiceMetaInfo";

// Load service ? autoload ?
//require_once "../project/apps/{$boundedContextCamelCaseUcFirst}/web-services/{$serviceCamelCase}/{$apiTypeCamelCase}/{$versionCamelCase}/Service.php";

$handlerObject = $di->getService($handlerServiceName);
$handlerObjectMetaInfo = $di->getService($handlerServiceMetaInfoName);

if (isset($_GET['wsdl'])) {
    $autodiscover = new Msc_SoapAutoDiscover(
        'Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex',
        $autoDiscoverUrl
    );
    $autodiscover->setClass($handlerServiceClassName, 'oxy');
    $autodiscover->handle();
} else {
    $server = new Zend_Soap_Server($wsdlUrl);
    $server->setObject($handlerObject); 
    $server->setClassmap($handlerObjectMetaInfo->getClassMap());
    $server->handle();
}