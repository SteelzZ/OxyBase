<?php
/**
 * Oxy_Domain_AggregateRoot_AbstractEventSourced test case.
 */
class Oxy_Domain_AggregateRoot_AbstractEventSourcedTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Oxy_Domain_AggregateRoot_AbstractEventSourced
     */
    private $_abstractEventSourced;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated Oxy_Domain_AggregateRoot_AbstractEventSourcedTest::setUp()
        $this->_abstractEventSourced = $this->getMockForAbstractClass(
            'Oxy_Domain_AggregateRoot_AbstractEventSourced', array(
                new Oxy_Guid()
            ));
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated Oxy_Domain_AggregateRoot_AbstractEventSourcedTest::tearDown()
        $this->_abstractEventSourced = null;
        parent::tearDown();
    }

    public function testShouldConstructAggregateRoot ()
    {
        $this->_abstractEventSourced->__construct(new Oxy_Guid());
        $this->assertAttributeType('Oxy_Domain_Event_Container_ContainerInterface', '_appliedEvents', 
            $this->_abstractEventSourced, 
            'AR events container must implement Oxy_Domain_Event_Container_ContainerInterface interface');
        $this->assertAttributeType('Oxy_Guid', '_guid', $this->_abstractEventSourced, 'AR guid must be Oxy_Guid type');
        $this->assertAttributeType('integer', '_version', $this->_abstractEventSourced, 
            'AR version must be valid integer');
        $this->assertAttributeEquals(0, '_version', $this->_abstractEventSourced, 
            'AR version after initializing should be equal to 0');	
    }
}