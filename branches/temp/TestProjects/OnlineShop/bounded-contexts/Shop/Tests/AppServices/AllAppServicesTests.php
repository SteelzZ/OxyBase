<?php
namespace Shop\Tests\AppServices; 

/**
 * Static test suite.
 */
class AllAppServicesTests extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('Shop\Tests\AppServices\AllAppServicesTests');
        $this->addTestSuite('Shop\Tests\AppServices\Read\ShopServiceTest');
        $this->addTestSuite('Shop\Tests\AppServices\Write\ShopServiceTest');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}
