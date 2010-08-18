<?php
/**
 * Base Aggregate Root class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_AggregateRoot
 */
abstract class Oxy_Domain_AggregateRoot_Abstract implements 
    Oxy_EventStore_EventProvider_Interface,
    Oxy_EventStore_Storage_Memento_Orginator_Interface,
    Oxy_EventStore_Storage_SnapShot_Interface
{
    /**
     * @var Oxy_Guid
     */
    protected $_guid;

    /**
     * @var integer
     */
    protected $_version;

    /**
     * @var Array
     */
    protected $_appliedEvents;

    /**
     * @var Array
     */
    protected $_childEntities;

    /**
     * @return integer $version
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * @return Oxy_Guid
     */
    public function getGuid()
    {
        return $this->_guid;
    }
    
    /**
     * @return Oxy_Domain_Event_Container_ContainerInterface
     */
    public function getChanges()
    {
        return $this->_appliedEvents;
    }
    
    /**
     * @param Oxy_Guid $guid
     */
    public function __construct(Oxy_Guid $guid)
    {
        $this->_appliedEvents = new Msc_Domain_Event_Container();
        $this->_childEntities = array();
        $this->_guid = $guid;
    }

    /**
     * Register child entity event
     *
     * @param Oxy_Domain_Entity_Abstract $childEntity
     * @param Oxy_Domain_Event_Interface $event
     *
     * @return void
     */
    public function registerChildEntityEvent(
        Oxy_Domain_Entity_Abstract $childEntity,
        Oxy_Domain_Event_Interface $event)
    {
        $this->_childEntities[(string)$childEntity->getGuid()] = $childEntity;
        $this->_appliedEvents->addEvent($childEntity->getGuid(), $event);
    }

    /**
     * Handle event
     *
     * @param Oxy_Domain_Event_Interface $event
     *
     * @return void
     */
    public function handleEvent(Oxy_Domain_Event_Interface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_appliedEvents->addEvent($this->getGuid(), $event);
        // Apply event - change state
        $this->apply($event);
    }

    /**
     * Apply event - change state
     *
     * @param Oxy_Domain_Event_Interface $event
     * @return void
     */
    protected function apply(Oxy_Domain_Event_Interface $event)
    {
        $eventName = 'on' . $event->getEventName();
        call_user_func_array(array($this, $eventName), array($event));
    }

    /**
     * Update version
     *
     * @param integer $version
     */
    public function updateVersion($version)
    {
        $this->_version = $version;
    }

    /**
     * Load events
     *
     * @param Oxy_Domain_Event_Container_ContainerInterface $domainEvents
     */
    public function loadFromHistory(Oxy_Domain_Event_Container_ContainerInterface $domainEvents)
    {
        $events = $domainEvents->getIterator();
        if (is_array($events)) {
            foreach ($events as $key => $eventData) {
                if ($eventData['eventProviderId'] == $this->_guid) {
                    $this->apply($eventData['event']);
                } else {
                    $childEntity = $this->_childEntities[$eventData['eventProviderId']];
                    $childEntity->loadFromHistory(
                        new Msc_Domain_Event_Container(
                            array($eventData['eventProviderId'] => $eventData['event'])
                        )
                    );
                }
            }
        }
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->_appliedEvents = new Oxy_Domain_Event_Container();
    }
}