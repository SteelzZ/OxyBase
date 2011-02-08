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
        $this->_abstractEventSourced = null;
        parent::tearDown();
    }
    
    private function getEventMock()
    {
        $eventMock = $this->getMock(
        	'Oxy_EventStore_Event_Interface'
        );
        
        $storableEventMock = $this->getMock(
        	'Oxy_EventStore_Event_StorableEventInterface'
        );        
    }

    public function testShouldConstructCorrectFreshEventSourcedAggregateRoot()
    {
        $this->_abstractEventSourced->__construct(new Oxy_Guid());
        
        $this->assertAttributeType(
            'Oxy_Domain_Entity_EventSourcedEntitiesCollection', 
            '_childEntities', 
            $this->_abstractEventSourced, 
            'Child entities collection'
        );	
        
        $this->assertAttributeEquals(
        	null, 
        	'_aggregateRoot', 
            $this->_abstractEventSourced, 
            'AR property must be null'
        );
    }
    
    public function testShouldLoadEventsFromHistoryAndRestoreState()
    {
        $this->_abstractEventSourced->__construct(new Oxy_Guid());
      
        $domainEvents = new Oxy_EventStore_Event_StorableEventsCollection(
            array(
                Oxy_Guid::FAKE_GUID => array(
                    'EventNameF1' => $this->getEventMock(),
                    'EventNameF2' => $this->getEventMock(),
                    'EventNameF3' => $this->getEventMock(),
                    'EventNameF4' => $this->getEventMock(),
                    'EventNameF5' => $this->getEventMock(),
                ),
                'guid-2' => array(
                    'EventName21' => $this->getEventMock(),
                    'EventName22' => $this->getEventMock(),
                    'EventName23' => $this->getEventMock(),
                    'EventName24' => $this->getEventMock(),
                    'EventName25' => $this->getEventMock(),
                ),
                'guid-3' => array(
                    'EventName31' => $this->getEventMock(),
                    'EventName32' => $this->getEventMock(),
                    'EventName33' => $this->getEventMock(),
                    'EventName34' => $this->getEventMock(),
                    'EventName35' => $this->getEventMock(),
                ),
            )
        );
        
        $this->_abstractEventSourced->loadEvents($domainEvents);
    }
}