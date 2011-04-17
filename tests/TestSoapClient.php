<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../project/'));
    
// Define path to tests directory
defined('TESTS_PATH')
    || define('TESTS_PATH', realpath(dirname(__FILE__) . '/../tests/'));

// Define application environment
define('APPLICATION_ENV', 'development');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    realpath(TESTS_PATH . '/library'),
    get_include_path(),
)));

/** Oxy_Application */
require_once 'Amqp/amqp.inc';
require('Symfony/sfServiceContainerAutoloader.php');
sfServiceContainerAutoloader::register();

require_once '../project/build/di/output/ApplicationContainer.php';
$appContainer = new ApplicationContainer();

require_once 'Oxy/Application.php';
// Create application and bootstrap
$application = new Oxy_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/config.xml'
);

$app = $application->bootstrap();
$app->bootstrap();

// ****** LOCAL *****//
// http://r100.msa.local.oxybase.com/api/account/account/general/v1r0/wsdl

fwrite(STDOUT, 'WSDL url?' . PHP_EOL);
$url = trim(fgets(STDIN));
ini_set("soap.wsdl_cache_enabled", "0");
$client = new Zend_Soap_Client(
    $url
);

$ownerInformation = array(
    'firstName' => 'Tomas',
    'lastName' => 'Bartkus',
    'dateOfBirth' => '1984-11-26',
    'gender' => 'male',
    'nickName' => 'Meilas',
    'mobileNumber' => '0037068212354',
    'homeNumber' => '0037068212354',
    'additionalInformation' => 'Something',
);

$deliveryInformation = array(
    'country' => 'Lithuania',
    'city' => 'Kaunas',
    'postCode' => '50135',
    'street' => 'Street',
    'houseNumber' => '114',
    'secondAddressLine' => 'Street2',
    'thirdAddressLine' => 'Street3',
    'additionalInformation' => 'Info'
);

$additionalSettings = array(
    'locale' => array(
        'country' => array(
            'code' => 'GB',
            'title' => 'United Kingdom'
        ),
        'language' => array(
            'code' => 'DK',
            'title' => 'Denmark'
        ),
    ), 
    'emailingTemplate' => 'default',
    'originalReferrer' => array(
       'brand' => 'my-website-com'
    )
);

$newProducts = array(
	'some-product' => array(
		'name' => 'some-product',
		'title' => 'My Cool product',
		'version' => '1', // 1 user version
		'duration' => '12', // license will expire in 12 month
		'license' => 'license-string', // license code
		'licenseType' => 'full', // license type
		'settings' => array() // additional things
	),
);

$newDevices = array(
	'iphone' => array(
		'deviceName' => 'iphone',
		'deviceTitle' => 'With spaces',
		'deviceType' => 'MOBILE',
		'operatingSystem' => 'NA',
		'operatingSystemType' => 'NA',
		'settings' => array(
			'some-product' => array(
				'settings' => ''
			)
		)
	),
	'laptop' => array(
		'deviceName' => 'laptop',
		'deviceTitle' => 'With spaces',
		'deviceType' => 'LAPTOP', // getConstants()
		'operatingSystem' => 'WINDOWS', // getConstants()
		'operatingSystemType' => 'OS_64', // getConstants()
		'settings' => array(),
	)
);

$actions = array(
	'1'  => array('operation' => 'getAccountInformation', 'name' => 'View account information'),
	'2'  => array('operation' => 'setupAccount', 'name' => 'Create new account'),
	'3'  => array('operation' => 'activateAccount', 'name' => 'Activate account'),
	'4'  => array('operation' => 'login', 'name' => 'Login'),
	'5'  => array('operation' => 'logout', 'name' => 'Logout'),
	'11' => array('operation' => 'addNewProductsInAccount', 'name' => 'Add new products'),
	'12' => array('operation' => 'addNewDevicesInAccount', 'name' => 'Add new devices'),
);

print PHP_EOL;

foreach ($actions as $key => $action) {
	print $key . '. ' . $action['name'] . PHP_EOL;
}

fwrite(STDOUT, 'What todo now?');
$action = trim(fgets(STDIN));

switch ($action) {
	case '1':
        fwrite(STDOUT, "Email ? \n");
        $mail = trim(fgets(STDIN));
        $s = microtime(true);
        $result = $client->getAccountInformation($mail);
        $r = microtime(true) - $s;
        print "Request time: ". $r . "\n";
        print "Result: \n";
        var_dump($result);
		break;
	case '2':
		fwrite(STDOUT, "Email ? \n");
        $mail = trim(fgets(STDIN));
        $s = microtime(true);
        $result = $client->getAccountProductsInformation($mail);
        $r = microtime(true) - $s;
        print "Request time: ". $r . "\n";
        print "Result: \n";
        var_dump($result);
		break;
	case '3':
		fwrite(STDOUT, "Email ? \n");
        $mail = trim(fgets(STDIN));
        $s = microtime(true);
        $result = $client->getAccountDevicesInformation($mail);
        $r = microtime(true) - $s;
        print "Request time: ". $r . "\n";
        print "Result: \n";
        var_dump($result);
		break;
	case '4':
        fwrite(STDOUT, "Email ? \n");
        $mail = trim(fgets(STDIN));
        fwrite(STDOUT, "Password ? \n");
        $password = trim(fgets(STDIN));
        $s = microtime(true);
        $client->setupAccount($mail, $password, $password, $ownerInformation, $deliveryInformation, $additionalSettings);
        $r = microtime(true) - $s;
        print "Request time: ". $r . "\n";
        break;
	case '5':
        fwrite(STDOUT, "Email ? \n");
        $mail = trim(fgets(STDIN));
        fwrite(STDOUT, "Activation key ? \n");
        $key = trim(fgets(STDIN));
        $s = microtime(true);
        $result = $client->activateAccount($mail, $key);
        $r = microtime(true) - $s;
        print "Request time: ". $r . "\n";
        print "Result: \n";
        var_dump($result);
		break;
	case '11':
		fwrite(STDOUT, "Email ? \n");
        $mail = trim(fgets(STDIN));
        $s = microtime(true);
        $result = $client->addNewProductsInAccount($mail, $newProducts);
        $r = microtime(true) - $s;
        print "Request time: ". $r . "\n";
        print "Result: \n";
        var_dump($result);
		break;
	case '12':
		fwrite(STDOUT, "Email ? \n");
        $mail = trim(fgets(STDIN));
        $s = microtime(true);
        $result = $client->addNewDevicesInAccount($mail, $newDevices);
        $r = microtime(true) - $s;
        print "Request time: ". $r . "\n";
        print "Result: \n";
        var_dump($result);
		break;
	default:
		break;
}