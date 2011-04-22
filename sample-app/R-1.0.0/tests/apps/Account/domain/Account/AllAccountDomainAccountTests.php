<?php
require_once 'PHPUnit/Framework/TestSuite.php';
include 'apps/Account/domain/Account/AccountTest.php';

/**
 * Static test suite.
 */
class AllAccountDomainAccountTests extends PHPUnit_Framework_TestSuite
{
    /**
     * Data fixture file
     */
    private $str_fixture;


    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('AllAccountDomainAccountTests');
        $this->addTestSuite('Account_Domain_Account_AccountTest');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}