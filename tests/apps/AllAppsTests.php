<?php
require_once 'PHPUnit/Framework/TestSuite.php';

include 'apps/Account/AllAccountTests.php';

/**
 * Static test suite.
 */
class AllAppsTests extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('AllAppsTests');
        $this->addTestSuite('AllAccountTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}
