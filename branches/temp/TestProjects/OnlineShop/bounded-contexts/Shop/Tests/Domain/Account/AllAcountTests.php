<?php
namespace Shop\Tests\Domain\Account; 

/**
 * Static test suite.
 */
class AllAcountTests extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('Shop\Tests\Domain\Account\AllAcountTests');
        $this->addTestSuite('Shop\Tests\Domain\Account\AggregateRoots\AccountTest');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}
