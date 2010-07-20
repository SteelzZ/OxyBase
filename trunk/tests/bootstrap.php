<?php
function init() {
    // Define path to application directory
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../project/'));
        
    // Define path to tests directory
    defined('TESTS_PATH')
        || define('TESTS_PATH', realpath(dirname(__FILE__) . '/../tests/'));

    // Define application environment
    define('APPLICATION_ENV', 'testing');

    // Ensure library/ is on include_path
    set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH . '/library'),
        realpath(TESTS_PATH . '/library'),
        get_include_path(),
    )));

    /** Oxy_Application */
    require_once 'Oxy/Application.php';

    // Create application, bootstrap, and run
    $application = new Oxy_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/config/config.xml'
    );

    $application->bootstrap();
}

init();