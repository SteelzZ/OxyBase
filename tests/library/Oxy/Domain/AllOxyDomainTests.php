<?php
require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'library/Oxy/Domain/AggregateRoot/AbstractEventSourcedTest.php';

/**
 * Static test suite.
 */
class AllOxyDomainTests extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('AllOxyDomainTests');
        $this->addTestSuite('Oxy_Domain_AggregateRoot_AbstractEventSourcedTest');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}



