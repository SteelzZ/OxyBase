<?php
class Oxy_Domain_Entity_EventSourcedAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Oxy_Domain_Entity_EventSourcedAbstract
     */
    private $_eventSourcedAbstract;
    
    /**
     * @var Oxy_Domain_AggregateRoot_EventSourcedAbstract
     */
    private $_eventSourcedArAbstract;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        $this->_eventSourcedArAbstract = $this->getMockForAbstractClass(
            'Oxy_Domain_AggregateRoot_EventSourcedAbstract', 
            array(
                new Oxy_Guid(Oxy_Guid::FAKE_GUID)
            )
        );
        $this->_eventSourcedAbstract = $this->getMockForAbstractClass(
            'Oxy_Domain_Entity_EventSourcedAbstract', 
            array(
                new Oxy_Guid('10000000-0000-0000-0000-000000000000'),
                $this->_eventSourcedArAbstract
            )
        ); 
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated Oxy_Domain_Entity_EventSourcedAbstractTest::tearDown()
        $this->_eventSourcedAbstract = null;
        parent::tearDown();
    }
    
    public function testShouldConstructCorrectFreshEventSourcedAggregateRoot()
    {
        $this->_eventSourcedAbstract->__construct(
            new Oxy_Guid(),
            $this->_eventSourcedArAbstract
        );
        
        $this->assertAttributeType(
        	'Oxy_Domain_AggregateRoot_EventSourcedAbstract', 
        	'_aggregateRoot', 
            $this->_eventSourcedAbstract, 
            'AR must be an instance of Oxy_Domain_AggregateRoot_EventSourcedAbstract'
        );
        
        $this->assertAttributeType(
        	'Oxy_Domain_Event_Collection', 
        	'_appliedEvents', 
            $this->_eventSourcedAbstract, 
            'AR events container must be an instance of Oxy_Collection_Interface'
        );
        
        $this->assertAttributeType(
        	'Oxy_Guid', 
        	'_guid', 
            $this->_eventSourcedAbstract, 
            'AR guid must be Oxy_Guid type'
        );
        
        $this->assertAttributeEquals(
            null, 
            '_version', 
            $this->_eventSourcedAbstract, 
            'AR version after initializing should be null'
        );	
    }
    
	/**
     * @expectedException Oxy_Domain_Exception
     */
    public function testShouldLoadEventsFromHistory()
    {
        $entityEvent = $this->getMock(
        	'Oxy_Domain_EventInterface'
        );
        
        $entityEvent->expects($this->any())
                    ->method('getEventName')
                    ->will($this->returnValue('EntityEvent'));
                           
        $entityEvent->expects($this->any())
                    ->method('toArray')
                    ->will($this->returnValue(array('property1' => 'value1', 'property2' => 'value2')));

                        
        $storableEntityEvent = $this->getMock(
        	'Oxy_Domain_Event_StorableEventInterface'
        );
        
        $storableEntityEvent->expects($this->any())
                        ->method('getProviderGuid')
                        ->will($this->returnValue('10000000-0000-0000-0000-000000000000'));
                        
        $storableEntityEvent->expects($this->any())
                            ->method('getEvent')
                            ->will($this->returnValue($entityEvent));
        
        $storableEvents = new ArrayIterator(
            array(
                $storableEntityEvent
            )
        );
        
        
        $domainEvents = $this->getMock(
        	'Oxy_Domain_Event_StorableEventsCollection'
        );
        
        $domainEvents->expects($this->any())
                     ->method('getIterator')
                     ->will($this->returnValue($storableEvents));
                                            
        $this->_eventSourcedAbstract->loadFromHistory(
            $domainEvents
        );
    }
}