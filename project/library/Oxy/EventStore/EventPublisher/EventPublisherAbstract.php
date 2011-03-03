<?php
/**
 * Events publisher base class
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage EventPublisher
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_EventStore_EventPublisher_EventPublisherAbstract 
    implements Oxy_EventStore_EventPublisher_EventPublisherInterface
{
    /**
     * @var array
     */
    protected $_listeners;

    /**
     * Map of (eventName => eventHandler instance)
     *
     * @var array
     */
    protected $_eventHandlers;

    /**
     * Map that shows what handlers
     * handles what events
     *
     * @var array
     */
    protected $_eventsAndHandlersMap;

    /**
     * Initialize publisher
     *
     * @param Array $options
     *
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (isset($options['eventsHandlersMap']) && is_array($options['eventsHandlersMap'])) {
            $this->setEventsHandlersMap($options['eventsHandlersMap']);
        }
        if (isset($options['eventHandlers']) && is_array($options['eventHandlers'])) {
            $this->setEventHandlers($options['eventHandlers']);
        }
    }

    /**
     * Set events handlers map
     *
     * @param Array $eventsHandlersMap
     */
    public function setEventsHandlersMap(Array $eventsHandlersMap = array())
    {
        $this->_eventsAndHandlersMap = array();
        if (is_array($eventsHandlersMap)) {
            foreach ($eventsHandlersMap as $eventName => $eventHandlers) {
                // Normalize, because of Zend problem
                $collectionOfHandlers = array();
                if (is_string($eventHandlers)) {
                    $collectionOfHandlers[] = $eventHandlers;
                } else if (is_array($eventHandlers)) {
                    $collectionOfHandlers = $eventHandlers;
                }
                if (! Zend_Validate::is($eventName, 'Alnum')) {
                    throw new Oxy_EventStore_EventPublisher_Exception(
                        "Event name must be valid string! [{$eventName}] is not valid"
                    );
                }
                if (! is_array($collectionOfHandlers)) {
                    throw new Oxy_EventStore_EventPublisher_Exception(
                        "Event handlers must be an array! eventname => array of eventHandlers"
                    );
                }
                if (isset($this->_eventsAndHandlersMap[$eventName])) {
                    throw new Oxy_EventStore_EventPublisher_Exception(
                        "Event already exist in map? Check map, it seems you have set event as key twice!"
                    );
                }
                $this->_eventsAndHandlersMap[$eventName] = $collectionOfHandlers;
            }
        }
    }

    /**
     * Return handlers map
     *
     * @return array
     */
    public function getMap()
    {
        return $this->_eventsAndHandlersMap;
    }

    /**
     * Set event handlers
     *
     * @param Array $eventHandlers
     */
    public function setEventHandlers(Array $eventHandlers = array())
    {
        $this->clearAll();
        if (empty($this->_eventsAndHandlersMap)) {
            throw new Oxy_EventStore_EventPublisher_Exception(
                "Events => Eventhandlers map is empty! Set it first, before setting handlers!"
            );
        }
        if (is_array($eventHandlers)) {
            foreach ($eventHandlers as $eventHandlerName => $eventHandlerCallbackData) {
                // Bad handler name
                if (! Zend_Validate::is($eventHandlerName, 'Alnum')) {
                    throw new Oxy_EventStore_EventPublisher_Exception(
                        "Event handler name must be valid string! [{$eventHandlerName}] is not valid"
                    );
                }
                if (isset($this->_eventHandlers[$eventHandlerName])) {
                    throw new Oxy_EventStore_EventPublisher_Exception(
                        "Check map, it seems you have set event handler name as key twice!"
                    );
                }
                $this->_eventHandlers[$eventHandlerName] = $eventHandlerCallbackData;
            }
        }
        
        if (!empty($this->_eventHandlers)) {
            $this->reAttachEventHandlers();
        }
    }

    /**
     * Re-attach event handlers
     *
     * We need be able to notify only some of handlers, that is done
     * by passign correct map
     *
     * This method is basically for more flexible usage
     * In DI we create our event publisher with default events and handlers
     * well basically in DI we define everything, but later in application
     * we can pass another map and re-attach listeners by given map
     * So, event handlers will be already loaded but we can change
     * what events they are listening
     *
     * @param Array $map
     */
    public function reAttachEventHandlers(Array $map = array())
    {
        $this->clearAll();
        if (!empty($map)) {
            $this->setEventsHandlersMap($map);
        } else if (empty($this->_eventsAndHandlersMap)) {
            throw new Oxy_EventStore_EventPublisher_Exception("Event handler map must be set!");
        }
        foreach ($this->_eventHandlers as $eventHandlerName => $eventHandlerCallbackData) {
            if (! Zend_Validate::is($eventHandlerName, 'Alnum')) {
                throw new Oxy_EventStore_EventPublisher_Exception(
                    "Event handler name must be valid string! [{$eventHandlerName}] is not valid"
                );
            }
            $this->attachListenerByMap($eventHandlerName, $eventHandlerCallbackData);
        }
    }

    /**
     * Attach listener
     *
     * Go through the map and see where listener must be attached
     *
     * @param string $eventHandlerName
     * @param array $eventHandlerCallbackData
     */
    public function attachListenerByMap($eventHandlerName, $eventHandlerCallbackData)
    {
        if (is_array($this->_eventsAndHandlersMap)) {
            foreach ($this->_eventsAndHandlersMap as $eventName => $allEventListeners) {
                if (in_array($eventHandlerName, $allEventListeners)) {
                    $this->attach($eventName, $eventHandlerCallbackData);
                }
            }
        }
    }

    /**
     * Clear all listeners
     */
    public function clearAll()
    {
        $this->_listeners = array();
    }

    /**
     * Attach a listener to a given event name.
     *
     * @param String  $name      An event name
     * @param Mixed   $listener  callback to create listener
     */
    public function attach($name, $listener)
    {
        $this->_listeners[$name][] = $listener;
    }

    /**
     * Detach a listener for a given event name.
     *
     * @param string   $name      An event name
     * @param mixed    $listener  A PHP callable
     *
     * @return bool true if listener detached, false otherwise
     */
    public function detach($name, $listener)
    {
        $result = false;
        if (isset($this->_listeners[$name])) {
            foreach ($this->_listeners[$name] as $i => $callable) {
                if ($listener === $callable) {
                    unset($this->_listeners[$name][$i]);
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * Returns true if the given event name has some listeners.
     *
     * @param  string   $str_name    The event name
     *
     * @return Boolean true if some listeners are connected, false otherwise
     */
    public function hasListeners($name)
    {
        if (isset($this->_listeners[$name]) && $this->_listeners[$name]) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Returns all listeners associated with a given event name.
     *
     * @param  string   $str_name    The event name
     *
     * @return array  An array of listeners
     */
    public function getListeners($name)
    {
        if (isset($this->_listeners[$name])) {
            $result = $this->_listeners[$name];
        } else {
            $result = array();
        }
        return $result;
    }

    /**
     * Notify all listeners
     * On demand get listener and notify it
     *
     * @param Oxy_EventStore_Event_StorableEventsCollectionInterface $events
     * @return void
     */
    public function notifyListeners(Oxy_EventStore_Event_StorableEventsCollectionInterface $events)
    {
        if($events->count() > 0){
            foreach ($events as $storableEvent) {
                $event = $storableEvent->getEvent();
                if ($event instanceof Oxy_EventStore_Event_EventInterface) {
                    foreach ($this->getListeners($event->getEventName()) as $listenerCallbackData) {
                        $eventHandlerInstance = call_user_func_array(
                            $listenerCallbackData['callback'],
                            $listenerCallbackData['param']
                        );
                        if ($eventHandlerInstance instanceof Oxy_EventStore_EventHandler_EventHandlerInterface) {
                            call_user_func_array(
                                array($eventHandlerInstance, 'handle'),
                                array($event)
                            );
                        }
                    }
                }
            }
        }
    }
}