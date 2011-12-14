<?php
namespace Shop\Tests\AppServices\Write; 

use Shop\AppServices\Write\ShopService; 

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
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_shService = new ShopService();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    public function testShouldAddCommandToMessageQueue()
    {   
    	$this->assertTrue(true);
    }
}