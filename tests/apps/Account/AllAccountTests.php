<?php
require_once 'PHPUnit/Framework/TestSuite.php';
include 'apps/Account/domain/AllAccountDomainTests.php';

/**
 * Static test suite.
 */
class AllAccountTests extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('AllAccountTests');
        $this->addTestSuite('AllAccountDomainTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}
