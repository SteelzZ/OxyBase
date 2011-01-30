<?php
/**
 * MongoDB implementation
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_EventStore_Storage_MongoDb implements Oxy_EventStore_Storage_Interface
{
    /**
     * @var Mongo
     */
    private $_db;

    /**
     * @var integer
     */
    private $_version;

    /**
     * @var string
     */
    protected $_dbName;
    
    /**
     * @return void
     */
    public function __construct(Mongo $db, $dbName)
    {
        $this->_db = $db->selectDB($dbName);
        $this->_version = 0;
        $this->_dbName = $dbName;
    }

    /**
     * Return version
     *
     * @param Oxy_guid $eventProviderGuid
     * @return integer
     */
    public function getVersion(Oxy_guid $eventProviderGuid)
    {
        return $this->_version;
    }
    
    /**
     * Reset version
     */
    public function resetVersion()
    {
        $this->_version = array();
    }   

    /**
     * Get snapshot
     *
     * @param Oxy_guid $eventProviderId
     * @return Msc_EventStore_Storage_SnapShot_Interface|null
     */
    public function getSnapShot(Oxy_guid $eventProviderGuid)
    {
        try{
            $collection = $this->_db->selectCollection('aggregates');
            $query = array(
                "_id" => (string)$eventProviderGuid
            );
            
            $cursor = $collection->findOne($query);
            if(is_null($cursor)) {
                return null;
            } else if (!class_exists((string)$cursor['sc'])){
                $this->_version = $cursor['v'];
                return null;
            } else {
                $this->_version = $cursor['v'];
            }
                    
            $snapshot = new Msc_EventStore_Storage_SnapShot(
                $eventProviderGuid, 
                $cursor['v'], 
                new $cursor['sc']((array)$cursor['ss'])
            );
                      
            return $snapshot;
        } catch(MongoCursorException $ex){
            return null;
        } catch(MongoConnectionException $ex){
             return null;
        } catch(MongoCursorTimeoutException $ex){
             return null;
        } catch(MongoGridFSException $ex){
             return null;
        } catch(MongoException $ex){
             return null;
        } catch (Exception $ex){
            return false;
        } 
    }

    /**
     * Return all events that are related
     * to $eventProviderId
     *
     * @param Oxy_guid $eventProviderId
     *
     * @return Msc_Domain_Event_Container_Interface
     */
    public function getAllEvents(Oxy_guid $eventProviderGuid)
    {
        try{
            $events = new Msc_Domain_Event_Container();
            $collection = $this->_db->selectCollection('events');
            $query = array(
                "ag" => (string)$eventProviderGuid
            );
                            
            $cursorAtEvents = $collection->find($query);
            foreach($cursorAtEvents as $eventData) {
                if(isset($eventData['eg'])){
                    if(class_exists($eventData['ec'])){
                        $events->addEvent(
                            new Oxy_guid($eventData['eg']), 
                            new $eventData['ec']($eventData['e'])
                        );
                    } 
                } else {
                    if(class_exists($eventData['ec'])){
                        $events->addEvent(
                            new Oxy_guid($eventData['ag']), 
                            new $eventData['ec']($eventData['e'])
                        );
                    } 
                }
            }     
                   
            return $events;  
        } catch(MongoCursorException $ex){
            return new Msc_Domain_Event_Container();
        } catch(MongoConnectionException $ex){
             return new Msc_Domain_Event_Container();
        } catch(MongoCursorTimeoutException $ex){
             return new Msc_Domain_Event_Container();
        } catch(MongoGridFSException $ex){
             return new Msc_Domain_Event_Container();
        } catch(MongoException $ex){
             return new Msc_Domain_Event_Container();
        } catch (Exception $ex){
            return false;
        } 
    }

    /**
     * Get events count since last snapshot
     *
     * @param Oxy_guid $eventProviderId
     * @return integer
     */
    public function getEventCountSinceLastSnapShot(Oxy_guid $eventProviderId)
    {
        return 0;        
    }

    /**
     * Get events since last snap shot
     *
     * @param Oxy_guid $eventProviderGuid
     * @return Msc_Domain_Event_Container_Interface
     */
    public function getEventsSinceLastSnapShot(Oxy_guid $eventProviderGuid)
    {
        return $this->getAllEvents($eventProviderGuid);
    }

    /**
     * Save events to database
     *
     * @param Msc_EventStore_EventProvider_Interface $eventProvider
     * 
     * @throws Msc_EventStore_Storage_ConcurrencyException
     * @throws Msc_EventStore_Storage_CouldNotSaveEventsException
     * @throws Msc_EventStore_Storage_CouldNotSaveSnapShotException
     * 
     * @return void
     */
    public function save(Msc_EventStore_EventProvider_Interface $eventProvider)
    {
        $changes = $eventProvider->getChanges();
        if($changes->count() > 0){
            
            if (!$this->checkVersion($eventProvider)) {
                throw new Oxy_EventStore_Storage_ConcurrencyException('Sorry concurrency problem!');
            }
            
            $result = $this->saveSnapShot($eventProvider, 0); 
            if($result){
                $result = $this->saveChanges($changes, $eventProvider->getGuid());
                if(is_null($result)){
                    throw new Oxy_EventStore_Storage_CouldNotSaveEventsException('Could not save events!');
                }
            } else {
                throw new Oxy_EventStore_Storage_CouldNotSaveSnapShotException('Could not save aggregate!');
            }
        }
        $aggregateGuid = $eventProvider->getGuid();       
    }

    /**
     * Save events to database
     *
     * @return integer
     */
    private function saveChanges(Msc_Domain_Event_Container_ContainerInterface $events, $guid)
    {
        try{
            $collection = $this->_db->selectCollection('events');
            $aggregateCollection = $this->_db->selectCollection('aggregates');
                
            // Add new events
            foreach ($events->getIterator() as $key => $eventData) {
                $event = (object)$eventData['event']->toArray();
                $eventProviderGuid = $eventData['eventProviderId'];
                
                if((string)$eventProviderGuid === (string)$guid){
                    $data = array(
                        'd' => date('Y-m-d H:i:s'),
                        'ag' => (string)$guid,
                        'e' => $event,
                        'ec' => (string)get_class($eventData['event'])
                    );
                } else {
                    $data = array(
                        'd' => date('Y-m-d H:i:s'),
                        'ag' => (string)$guid,
                        'eg' => (string)$eventProviderGuid,
                        'e' => $event,
                        'ec' => (string)get_class($eventData['event'])
                    );
                }
                                            
                $collection->insert($data, array("safe" => true));
                
                return true;
            }
        } catch(MongoCursorException $ex){
            return null;
        } catch(MongoConnectionException $ex){
             return null;
        } catch(MongoCursorTimeoutException $ex){
             return null;
        } catch(MongoGridFSException $ex){
             return null;
        } catch(MongoException $ex){
             return null;
        } catch (Exception $ex){
            return null;
        }  
    }
    
	/**
     * Save snapshot
     *
     * @param Msc_EventStore_EventProvider_Interface $eventProvider
     * @param integer $lastEventId
     */
    public function saveSnapShot(
        Msc_EventStore_EventProvider_Interface $eventProvider, 
        $lastEventId
    )
    {
        try{
            $aggregateCollection = $this->_db->selectCollection('aggregates');
            $memento = $eventProvider->createMemento();    
            if(!is_null($memento)){
                $aggregateCollection->update(
                    array("_id" => (string)$eventProvider->getGuid()), 
                    array(
                        '_id' => (string)$eventProvider->getGuid(),
                        'ss' => (object)$memento->toArray(),
                        'sc' => (string)get_class($memento),
                        'v' => $this->_version + 1
                    ), 
                    array("upsert" => true, "safe" => true)
                );  
            }
            return true;
        } catch(MongoCursorException $ex){
            return false;
        } catch(MongoConnectionException $ex){
             return false;
        } catch(MongoCursorTimeoutException $ex){
             return false;
        } catch(MongoGridFSException $ex){
             return false;
        } catch(MongoException $ex){
             return false;
        } catch (Exception $ex){
            return false;
        }  
    }

    /**
     * Check for concurency
     *
     * @param Msc_EventStore_EventProvider_Interface $eventProvider
     *
     * @return boolean
     */
    private function checkVersion(Msc_EventStore_EventProvider_Interface $eventProvider)
    {
        try{
            $collection = $this->_db->selectCollection('aggregates');
            $query = array(
                "_id" => (string)$eventProvider->getGuid()
            );
            
            $cursor = $collection->findOne($query);
            if(is_null($cursor)) {
                $this->_version = 1;
                return true;
            } 
            
            if ((int)$cursor['v'] === (int)$eventProvider->getVersion()) {
                $this->_version = (int)$cursor['v'];
                return true;
            } else {
                return false;
            }   
        } catch(MongoCursorException $ex){
            return false;
        } catch(MongoConnectionException $ex){
             return false;
        } catch(MongoCursorTimeoutException $ex){
             return false;
        } catch(MongoGridFSException $ex){
             return false;
        } catch(MongoException $ex){
             return false;
        } catch (Exception $ex){
            return false;
        }
    }
}