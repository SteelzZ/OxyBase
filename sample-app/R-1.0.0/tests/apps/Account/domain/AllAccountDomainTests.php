<?php
require_once 'PHPUnit/Framework/TestSuite.php';
include 'apps/Account/domain/Account/AllAccountDomainAccountTests.php';

/**
 * Static test suite.
 */
class AllAccountDomainTests extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('AllAccountDomainTests');
        $this->addTestSuite('AllAccountDomainAccountTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}



