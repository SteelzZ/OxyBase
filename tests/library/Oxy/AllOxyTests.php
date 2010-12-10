<?php
require_once 'PHPUnit/Framework/TestSuite.php';
include 'library/Oxy/Domain/AllOxyDomainTests.php';


/**
 * Static test suite.
 */
class AllOxyTests extends PHPUnit_Framework_TestSuite
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
        $this->setName('AllOxyTests');
        $this->addTestSuite('AllOxyDomainTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}