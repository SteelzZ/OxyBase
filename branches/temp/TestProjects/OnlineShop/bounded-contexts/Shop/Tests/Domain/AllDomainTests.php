<?php
namespace Shop\Tests\Domain; 

/**
 * Static test suite.
 */
class AllDomainTests extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('Shop\Tests\Domain\AllDomainTests');
        $this->addTestSuite('Shop\Tests\Domain\Account\AllAcountTests');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}
