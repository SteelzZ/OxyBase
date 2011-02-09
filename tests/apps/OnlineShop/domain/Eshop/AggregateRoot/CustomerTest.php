<?php
class OnlineShop_Domain_Eshop_AggregateRoot_CustomerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var OnlineShop_Domain_Eshop_AggregateRoot_Customer
     */
    private $_customer;
    
    /**
     * @var Oxy_Guid
     */
    private $_guid;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        // TODO Auto-generated OnlineShop_Domain_Eshop_AggregateRoot_CustomerTest::setUp()
        $this->_customer = new OnlineShop_Domain_Eshop_AggregateRoot_Customer(
            $this->_guid
        );
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated OnlineShop_Domain_Eshop_AggregateRoot_CustomerTest::tearDown()
        $this->_customer = null;
        parent::tearDown();
    }
    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        $this->_guid = new Oxy_Guid('my-user-guid');
    }
    
    public function testShouldGenerateEventsThatCustomerHasRegistered()
    {
        $expectedEvents = array(
            'my-user-guid' => array(
                0 => 'OnlineShop_Domain_Eshop_AggregateRoot_Customer_Event_CustomerRegistered'
            )
        );
        
        $this->_customer->registerForCustomer(
            OnlineShop_Domain_Eshop_ValueObject_Email
        );
        
        $events = $this->_customer->getChanges()->toArray();
        $this->assertEquals($expectedEvents, $events);
    }
}