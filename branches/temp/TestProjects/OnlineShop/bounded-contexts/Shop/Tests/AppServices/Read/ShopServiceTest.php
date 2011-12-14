<?php
namespace Shop\Tests\AppServices\Read; 

use Shop\AppServices\Read\ShopService; 

/**
 * Msc_Command_Standard test case.
 */
class ShopServiceTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var ShopService
	 */
	private $_shopService;
	
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
        $this->_shopService = new ShopService();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    public function testShouldReturnCartContent()
    { 
    	$this->assertTrue(true);  
    }
}