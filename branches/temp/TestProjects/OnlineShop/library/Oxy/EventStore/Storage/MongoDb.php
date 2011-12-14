<?php
/**
 * MongoDB implementation
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Storage;
use Oxy\EventStore\Storage\StorageInterface;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\EventStore\Storage\SnapShot\SnapShotInterface;
use Oxy\EventStore\Storage\SnapShot;
use Oxy\Guid;
use Oxy\EventStore\Event\StorableEventsCollectionInterface;
use Oxy\EventStore\Event\StorableEventsCollection;
use Oxy\EventStore\Storage\ConcurrencyException;
use Oxy\EventStore\Storage\CouldNotSaveEventsException;
use Oxy\EventStore\Storage\CouldNotSaveSnapShotException;
use Oxy\EventStore\Storage\EntityAlreadyExistsException;
use Oxy\EventStore\Event\ArrayableInterface;
use Oxy\EventStore\Storage\Exception;
     
class MongoDb implements StorageInterface
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
     * @return integer
     */
    public function getVersion()
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
     * @param Guid $eventProviderGuid
     * @param EventProviderInterface $eventProvider
     * 
     * @return SnapShotInterface|null
     */
    public function getSnapShot(
        Guid $eventProviderGuid, 
        EventProviderInterface $eventProvider
    )
    {
        try{
            $collection = $this->_db->selectCollection('aggregates');
            /*$query = array(
                "_id" => (string)$eventProviderGuid
            );
            */
            $query = array(
                "en" => (string)$eventProvider->getName(), // en - entityName
                "rei" => (string)$eventProvider->getRealIdentifier(), // rei - realEntityIdentifier
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
                    
            $snapshot = new SnapShot(
                new Guid($cursor['_id']), 
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
            return null;
        } 
    }

    /**
     * Return all events that are related
     * to $eventProviderId
     *
     * @param Guid $eventProviderGuid
     *
     * @return StorableEventsCollectionInterface
     */
    public function getAllEvents(Guid $eventProviderGuid)
    {
        try{
            $events = new StorableEventsCollection();
            $collection = $this->_db->selectCollection('events');
            $query = array(
                "ag" => (string)$eventProviderGuid
            );
                                        
            $cursorAtEvents = $collection->find($query);
            if($cursorAtEvents instanceof MongoCursor){
                $cursorAtEvents->timeout(-1);
                $cursorAtEvents->sort(array('_id' => 1));
                foreach($cursorAtEvents as $eventData) {
                    if(isset($eventData['eg'])){
                        if(class_exists($eventData['ec'])){
                            $events->addEvent(
                                new Oxy_EventStore_Event_StorableEvent(
                                    new Guid($eventData['eg']),
                                    new $eventData['ec']($eventData['e'])
                                )
                            );
                        } 
                    } else {
                        if(class_exists($eventData['ec'])){
                            $events->addEvent(
                                new Oxy_EventStore_Event_StorableEvent(
                                    new Guid($eventData['ag']),
                                    new $eventData['ec']($eventData['e'])
                                )
                            );
                        } 
                    }
                }     
            }
                   
            return $events;  
        } catch(MongoCursorException $ex){
            return new StorableEventsCollection();
        } catch(MongoConnectionException $ex){
             return new StorableEventsCollection();
        } catch(MongoCursorTimeoutException $ex){
             return new StorableEventsCollection();
        } catch(MongoGridFSException $ex){
             return new StorableEventsCollection();
        } catch(MongoException $ex){
             return new StorableEventsCollection();
        } catch (Exception $ex){
            return new StorableEventsCollection();
        } 
    }

    /**
     * Get events count since last snapshot
     *
     * @param Guid $eventProviderGuid
     * @return integer
     */
    public function getEventCountSinceLastSnapShot(Guid $eventProviderGuid)
    {
        return 0;        
    }

    /**
     * Get events since last snap shot
     *
     * @param Guid $eventProviderGuid
     * @return StorableEventsCollectionInterface
     */
    public function getEventsSinceLastSnapShot(Guid $eventProviderGuid)
    {
        //return $this->getAllEvents($eventProviderGuid);
        return new StorableEventsCollection();
    }

    /**
     * Save events to database
     *
     * @param EventProviderInterface $eventProvider
     * 
     * @throws Oxy_EventStore_Storage_ConcurrencyException
     * @throws Oxy_EventStore_Storage_CouldNotSaveEventsException
     * @throws Oxy_EventStore_Storage_CouldNotSaveSnapShotException
     * 
     * @return void
     */
    public function save(EventProviderInterface $eventProvider)
    {
        $changes = $eventProvider->getChanges();
        if($changes->count() > 0){
               
            $collection = $this->_db->selectCollection('aggregates');
            $query = array(
                "en" => (string)$eventProvider->getName(), // en - entityName
                "rei" => (string)$eventProvider->getRealIdentifier(), // rei - realEntityIdentifier
            );
            
            $cursor = $collection->findOne($query);            
            if (!$this->_checkVersion($cursor, $eventProvider->getVersion())) {
                throw new ConcurrencyException(
                	sprintf(
                		'Sorry concurrency problem!!'
                    )
                );
            }
            
            if (!$this->_isSame($cursor, $eventProvider->getGuid(), $eventProvider->getName(), $eventProvider->getRealIdentifier())) {
                throw new EntityAlreadyExistsException(
                	sprintf(
                		'Entity with id [%s] and name [%s] already exists!',
                	    $eventProvider->getRealIdentifier(),
                	    $eventProvider->getName()
                    )
                );
            }
            
            $result = $this->saveSnapShot($eventProvider); 
            if($result){
                $result = $this->saveChanges($changes, $eventProvider->getGuid());
                if(!$result){
                    throw new CouldNotSaveEventsException('Could not save events!');
                }
            } else {
                throw new CouldNotSaveSnapShotException('Could not save aggregate!');
            }
        }
        $aggregateGuid = $eventProvider->getGuid();       
    }

    /**
     * Save events to database
     *
     * @param StorableEventsCollectionInterface 
     * @param Guid $guid
     * 
     * @return null
     */
    private function saveChanges(
        StorableEventsCollectionInterface $events, 
        Guid $guid
    )
    {
        try{
            $collection = $this->_db->selectCollection('events');
            $aggregateCollection = $this->_db->selectCollection('aggregates');

            // Add new events
            foreach ($events as $storableEvent) {
                $eventInstance = $storableEvent->getEvent();
                if(!$eventInstance instanceof ArrayableInterface){
                   throw new Exception(
                       sprintf('Event must implement Oxy_EventStore_Event_ArrayableInterface interface')
                   ); 
                }
                $event = (object)$eventInstance->toArray();
                
                if((string)$storableEvent->getProviderGuid() === (string)$guid){
                    $data = array(
                        'd' => date('Y-m-d H:i:s'),
                        'ag' => (string)$guid,
                        'e' => $event,
                        'ec' => (string)get_class($eventInstance)
                    );
                } else {
                    $data = array(
                        'd' => date('Y-m-d H:i:s'),
                        'ag' => (string)$guid,
                        'eg' => (string)$storableEvent->getProviderGuid(),
                        'e' => $event,
                        'ec' => (string)get_class($eventInstance)
                    );
                }
                $collection->insert($data, array("safe" => true));
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
        
        return false;
    }

    /**
     * Save snapshot
     *
     * @param EventProviderInterface $eventProvider
     * @return void
     */
    public function saveSnapShot(
        EventProviderInterface $eventProvider
    )
    {
        try{
            $aggregateCollection = $this->_db->selectCollection('aggregates');
            $memento = $eventProvider->createMemento();    
            //var_dump($memento);
            if(!is_null($memento)){
                $aggregateCollection->update(
                    array("_id" => (string)$eventProvider->getGuid()), 
                    array(
                        '_id' => (string)$eventProvider->getGuid(),
                        'en' => (string)$eventProvider->getName(),
                        'rei' => (string)$eventProvider->getRealIdentifier(),
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
     * @param mixed $cursor
     * @param integer $version
     *
     * @return boolean
     */
    private function _checkVersion($cursor, $version)
    {
        try{
            if(is_null($cursor)) {
                $this->_version = 1;
                return true;
            } 
            
            if ((int)$cursor['v'] === (int)$version) {
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
        } catch(Exception $ex){
            return false;
        }
    }      

    /**
     * Check for concurency
     *
     * @param mixed $cursor
     * @param string $guid
     * @param string $name
     * @param string $realIdentifier
     *
     * @return boolean
     */
    private function _isSame($cursor, $guid, $name, $realIdentifier)
    {
        try{
            if(is_null($cursor)) {
                return true;
            } 
            
            if (
                ((string)$cursor['en'] === (string)$name) 
                && ((string)$cursor['rei'] === $realIdentifier) 
                && ((string)$cursor['_id'] === (string)$guid)
            ) {
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
        } catch(Exception $ex){
            return false;
        }
    }      
}