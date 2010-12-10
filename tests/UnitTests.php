<?php
require_once 'PHPUnit/Framework/TestSuite.php';
include 'library/AllLibraryTests.php';

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
        $this->addTestSuite('AllLibraryTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite ()
    {
        return new self();
    }
}
