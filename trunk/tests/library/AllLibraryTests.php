<?php
require_once 'PHPUnit/Framework/TestSuite.php';
include 'library/Oxy/AllOxyTests.php';
/**
 * Static test suite.
 */
class AllLibraryTests extends PHPUnit_Framework_TestSuite
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
        $this->setName('AllLibraryTests');
        $this->addTestSuite('AllOxyTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}