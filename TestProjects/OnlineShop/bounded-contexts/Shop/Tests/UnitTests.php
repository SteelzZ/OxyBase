<?php
namespace Shop\Tests; 

/**
 * Static test suite.
 */
class UnitTests extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('Shop\Tests\UnitTests');
        $this->addTestSuite('Shop\Tests\Domain\AllDomainTests');
        $this->addTestSuite('Shop\Tests\AppServices\AllAppServicesTests');
        $this->addTestSuite('Shop\Tests\Queries\AllQueriesTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}