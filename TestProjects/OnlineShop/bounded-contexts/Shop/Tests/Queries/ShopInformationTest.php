<?php
namespace Shop\Tests\Queries; 

use Shop\Queries\ShopInformation; 

/**
 * Msc_Command_Standard test case.
 */
class ShopInformationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var ShopInformation
	 */
	private $_shopInformation;
	
    /**
     * Constructs the test case.
     */
    public function __construct()
    {
    }
	
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_shopInformation = new ShopInformation();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    public function testShouldReturnShopInformation()
    { 
    	$this->assertTrue(true);  
    }
}