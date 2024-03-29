<?php
require_once 'PHPUnit/Framework/TestSuite.php';
include 'apps/AllAppsTests.php';

/**
 * Static test suite.
 */
class UnitTests extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct ()
    {
        $this->setName('UnitTests');
        $this->addTestSuite('AllAppsTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite ()
    {
        return new self();
    }
}
