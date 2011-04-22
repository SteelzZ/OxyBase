#!/usr/bin/php -q
<?php 
// Include Class
error_reporting(E_ALL);

// Allowed arguments & their defaults
$runmode = array(
    'no-daemon' => false,
    'help' => false,
    'write-initd' => false
);
 
// Scan command line attributes for allowed arguments
foreach ($argv as $k=>$arg) {
    if (substr($arg, 0, 2) == '--' && isset($runmode[substr($arg, 2)])) {
        $runmode[substr($arg, 2)] = true;
    }
}
 
// Help mode. Shows allowed argumentents and quit directly
if ($runmode['help'] == true) {
    echo 'Usage: '.$argv[0].' [runmode]' . "\n";
    echo 'Available runmodes:' . "\n";
    foreach ($runmode as $runmod=>$val) {
        echo ' --'.$runmod . "\n";
    }
    die();
}
  
$options = parse_ini_file($argv[1], null, INI_SCANNER_RAW);

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

$host = isset($options['messaging.broker.host']) ? 
                        $options['messaging.broker.host'] : 'tcp://guest:guest@localhost:5672/?txt-mode=true';

$host .= '|queue-id='.$queueName;

// @todo: perhaps define it explicitly ?
if(!isset($argv[4])) {
    throw new Exception('Specify command handler builder service name!');
}  
$commandHandlerBuilderService = $options['messaging.broker.command.handler.service.prefix'] . $argv[4];

// Setup
$options = array(
    'appName' => $consumerName,
    'appDir' => dirname(__FILE__),
    'runTemplateLocation' => dirname(__FILE__) . '/../build/resources/data/',
    'logLocation' => '/opt/logs/'.$consumerName.'.log',
    'appDescription' => 'Messages consumer deamon',
    'authorName' => 'Tomas Bartkus',
    'authorEmail' => 'tomas@mysecuritycenter.com',
    'sysMaxExecutionTime' => '0',
    'sysMaxInputTime' => '0',
    'sysMemoryLimit' => '1024M',
    'appRunAsGID' => 65534,
    'appRunAsUID' => 65534,
);
 
System_Daemon::setOptions($options);
System_Daemon::setOption("usePEAR", false);

// Create application, bootstrap, and run
$application = new Oxy_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/config.xml'
);

// This program can also be run in the forground with runmode --no-daemon
if (!$runmode['no-daemon']) {
    // Spawn Daemon
    System_Daemon::start();
}
 
// With the runmode --write-initd, this program can automatically write a
// system startup file called: 'init.d'
// This will make sure your daemon will be started on reboot
if (!$runmode['write-initd']) {
    System_Daemon::info('not writing an init.d script this time');
} else {
    if (($initd_location = System_Daemon::writeAutoRun()) === false) {
        System_Daemon::notice('unable to write init.d script');
    } else {
        System_Daemon::info(
            'sucessfully written startup script: %s',
            $initd_location
        );
    }
}
 
// Run your code
// Here comes your own actual code
 
// This variable gives your own code the ability to breakdown the daemon:
$runningOkay = true;
 
// This variable keeps track of how many 'runs' or 'loops' your daemon has
// done so far. For example purposes, we're quitting on 3.
$cnt = 1;
 
// While checks on 3 things in this case:
// - That the Daemon Class hasn't reported it's dying
// - That your own code has been running Okay
// - That we're not executing more than 3 runs
while (!System_Daemon::isDying() && $runningOkay && $cnt <=3) {
    // What mode are we in?
    $mode = '"'.(System_Daemon::isInBackground() ? '' : 'non-' ).
        'daemon" mode';
 
    // Log something using the Daemon class's logging facility
    // Depending on runmode it will either end up:
    //  - In the /var/log/logparser.log
    //  - On screen (in case we're not a daemon yet)
    System_Daemon::info('{appName} running in %s %s/3',
        $mode,
        $cnt
    );
 
    // In the actuall logparser program, You could replace 'true'
    // With e.g. a  parseLog('vsftpd') function, and have it return
    // either true on success, or false on failure.
    //$runningOkay = true;
    try{
        $s = microtime(true);
        require('symfony/sfServiceContainerAutoloader.php');
        sfServiceContainerAutoloader::register();
        require_once '../../../build/di/output/ApplicationContainer.php';
        $app = $application->bootstrap();
        $e = microtime(true);
        $elapsed = $e - $s;
        
        System_Daemon::info("Bootstraped in {$elapsed} seconds");
        
        $di = new ApplicationContainer();
        //$commandHandlerBuilder = $di->getService('oxyCqrsCommandHandlerBuilderStandard');
        $commandHandlerBuilder = $di->getService($commandHandlerBuilderService);
        $mongo = $di->getService('mongo');
        
        $s = microtime(true);
        $adapter = new Oxy_Queue_Adapter_Rabbitmq($host);
        $e = microtime(true);
        $elapsed = $e - $s;
        System_Daemon::info("Connected to broker @ host {$host} in {$elapsed} seconds");
        
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
            		'collection' => 'consumers-logs'
                )
           )
        );
        $e = microtime(true);
        $elapsed = $e - $s;
        System_Daemon::info("Configured consumer with {$consumerName} name in {$elapsed} seconds");

        $consumer->consume();
        $runningOkay = true;
    } catch (Exception $ex) {
        System_Daemon::err('Error while consuming messages '.
            $ex->getMessage()
        );
        System_Daemon::err('Trace '.
            $ex->getTraceAsString()
        );
        $runningOkay = false;        
    }
    
 
    // Should your parseLog('vsftpd') return false, then
    // the daemon is automatically shut down.
    // An extra log entry would be nice, we're using level 3,
    // which is critical.
    // Level 4 would be fatal and shuts down the daemon immediately,
    // which in this case is handled by the while condition.
    if (!$runningOkay) {
        System_Daemon::err('Exception while consuming so this will be my last run');
    }
 
    // Relax the system by sleeping for a little bit
    // iterate also clears statcache
    System_Daemon::iterate(2);
 
    $cnt++;
}
 
// Shut down the daemon nicely
// This is ignored if the class is actually running in the foreground
System_Daemon::stop();