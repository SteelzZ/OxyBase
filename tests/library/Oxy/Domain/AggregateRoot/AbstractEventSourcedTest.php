<?php
/**
 * Oxy_Domain_AggregateRoot_AbstractEventSourced test case.
 */
class Oxy_Domain_AggregateRoot_AbstractEventSourcedTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Oxy_Domain_AggregateRoot_EventSourcedAbstract
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
            'Oxy_Domain_AggregateRoot_EventSourcedAbstract', 
            array(
                new Oxy_Guid(Oxy_Guid::FAKE_GUID)
            )
        );
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

    public function testShouldConstructCorrectFreshEventSourcedAggregateRoot()
    {
        $this->_abstractEventSourced->__construct(new Oxy_Guid());
        
        $this->assertAttributeType(
            'Oxy_Domain_Entity_EventSourcedEntitiesCollection', 
            '_childEntities', 
            $this->_abstractEventSourced, 
            'AR version after initializing should be null'
        );	
        
        $this->assertAttributeEquals(
        	null, 
        	'_aggregateRoot', 
            $this->_abstractEventSourced, 
            'AR param mus be null'
        );
    }
}