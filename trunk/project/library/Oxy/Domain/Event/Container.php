<?php
/**
 * Event providers container
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Event
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
class Oxy_Domain_Event_Container implements Oxy_Domain_Event_Container_ContainerInterface
{
    /**
     * Events array
     *
     * @var Array
     */
    protected $_events;

    /**
     * Event providers
     *
     * @var Array
     */
    protected $_eventProviders;

    /**
     * Initialize
     *
     * @param Array $options
     * @return void
     */
    public function __construct(Array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Set options
     *
     * @param Array $options
     * @return void
     */
    public function setOptions(array $options)
    {
        foreach ($options as $eventProviderId => $event) {
            if (is_array($event)) {
                foreach ($event as $singleEvent) {
                    $this->addEvent(new Oxy_Guid($eventProviderId), $singleEvent);
                }
            } else {
                $this->addEvent(new Oxy_Guid($eventProviderId), $event);
            }
        }
    }

    /**
     * Add new event
     *
     * @param Oxy_Guid $eventProviderId
     * @param Oxy_Domain_Event_Interface $event
     * @return void
     */
    public function addEvent(Oxy_Guid $eventProviderId, Oxy_Domain_Event_Interface $event)
    {
        $eventProviderIdString = (string)$eventProviderId;
        $this->_eventProviders[$eventProviderIdString][][$event->getEventName()] = $event;
        $index = count($this->_events);
        $this->_events[$index]['event'] = $event;
        $this->_events[$index]['eventProviderId'] = $eventProviderIdString;
    }

    /**
     * Get All events for event provider
     *
     * @param string $eventProviderId
     * @return Array
     */
    public function get($eventProviderId)
    {
        if (isset($this->_eventProviders[$eventProviderId])) {
            $result = $this->_eventProviders[$eventProviderId];
        } else {
            $result = array();
        }
        return $result;
    }

    /**
     * Return events count
     *
     * @return integer
     */
    public function count()
    {
        return (int)(count($this->_events));
    }

    /**
     * To array
     *
     * @return Array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this->_events as $key => $data) {
            $result[] = $data['event'];
        }
        return $result;
    }

    /**
     * Return all events
     *
     * @return Array
     */
    public function getIterator()
    {
        return $this->_events;
    }
}