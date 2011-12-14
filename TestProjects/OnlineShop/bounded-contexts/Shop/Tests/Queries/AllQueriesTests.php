<?php
namespace Shop\Tests\Queries; 

/**
 * Static test suite.
 */
class AllQueriesTests extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('Shop\Tests\Queries\AllQueriesTests');
        $this->addTestSuite('Shop\Tests\Queries\ShopInformationTest');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}
