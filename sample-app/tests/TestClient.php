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

// Write services
$msaAccountManagementService = $appContainer->getService('accountLibServiceWriteAccountManagementService');

//************************************************************************************************//
// Test whole flow for my account - basic test
//************************************************************************************************//
$email = 'wazonikas@gmail.com';
$password = 'asdfgh';
$passwordAgain = 'asdfgh';
$ownerInformation = array(
    'firstName' => 'Tomas',
    'lastName' => 'Bartkus',
    'dateOfBirth' => '1984-11-26',
    'gender' => 'male',
    'nickName' => 'Meilas',
    'mobileNumber' => '0037068123462',
    'homeNumber' => '00370612317462',
    'additionalInformation' => 'Something',
);

$deliveryInformation = array(
    'country' => 'Lithuania',
    'city' => '5',
    'postCode' => '5',
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
    ) ,
    'acceptance' => array(
        'spam' => false,
        'terms' => true,
    ) ,
    'emailingTemplate' => 'default'
);

$email = 'wazonikas+201103311957@gmail.com';
// Step - 1 : Setup new account
$msaAccountManagementService->createNewAccount(
    $email, $password, $passwordAgain, $ownerInformation, $deliveryInformation, $additionalSettings
);

//**************************************************************************************************//