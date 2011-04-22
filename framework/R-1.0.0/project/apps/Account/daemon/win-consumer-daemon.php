<?php 
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', '../../../');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'daemon'));

// Ensure library/ is on include_path
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(realpath(APPLICATION_PATH . '/library'),
        get_include_path(),
    ))
);

/** Oxy_Application */
require_once 'Oxy/Application.php';
require_once 'Amqp/amqp.inc';
require_once 'System/Daemon.php';

$options = parse_ini_file($argv[1], null, INI_SCANNER_RAW);

$becomeListener = isset($options['messaging.consumer.become.listener']) ? 
                        (boolean)$options['messaging.consumer.become.listener'] : true;

if(!isset($argv[2])) {
    throw new Exception('Specify node number');
}                       
$number = $argv[2];        

if(!isset($options['messaging.consumer.name.prefix'])){
    throw new Exception('Specify consumer prefix!');
}
$consumerName = isset($options['messaging.consumer.name.prefix']) ? 
                        $options['messaging.consumer.name.prefix'] . $number : 'consumer-unknown-'.rand(1, 100000);

if(!isset($argv[3])) {
    throw new Exception('Specify queue name');
}                           
$queueName = $argv[3];                  
$host = isset($options['messaging.borker.host']) ? 
                        $options['messaging.borker.host'] : 'tcp://guest:guest@localhost:5672/?txt-mode=true';

$host .= '|queue-id='.$queueName;

// @todo: perhaps define it explicitly ?
if(!isset($argv[4])) {
    throw new Exception('Specify command handler builder service name!');
}  
$commandHandlerBuilderService = $options['messaging.borker.command.handler.service.prefix'] . $argv[4];

require('Symfony/sfServiceContainerAutoloader.php');
sfServiceContainerAutoloader::register();

require_once '../../../build/di/output/ApplicationContainer.php';
$di = new ApplicationContainer();

// Create application, bootstrap, and run
$application = new Oxy_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/config.xml'
);
$application->bootstrap();

try{    
    //$commandHandlerBuilder = $di->getService('oxyCqrsCommandHandlerBuilderStandard');
    $commandHandlerBuilder = $di->getService($commandHandlerBuilderService);
    $mongo = $di->getService('mongo');
    
    $s = microtime(true);
    $adapter = new Oxy_Queue_Adapter_Rabbitmq($host);
    $e = microtime(true);
    $elapsed = $e - $s;
    echo "Connected to broker @ host {$host} in {$elapsed} seconds \n";
    
    $s = microtime(true);
    
    $consumer = new Oxy_Cqrs_Command_Consumer_Amqp(
        $adapter, 
        $commandHandlerBuilder,
        array(
        	'become-listener' => $becomeListener, 
        	'consumer-name' => $consumerName, 
        	'log' => array(
        		'db' => $mongo, 
        		'dbName' => 'logs', 
        		'collection' => $options['messaging.consumer.name.prefix']
            )
       )
    );
    $e = microtime(true);
    $elapsed = $e - $s;
    echo "Configured consumer with {$consumerName} name in {$elapsed} seconds \n";
    echo "\n Niam niam :) \n";
    $consumer->consume();
} catch (Exception $ex) {
    echo 'Error while consuming messages '.$ex->getMessage();
    echo 'Trace '. $ex->getTraceAsString();      
}