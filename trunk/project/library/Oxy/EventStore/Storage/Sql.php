<?php
/**
 * SQL implementation
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
class Oxy_EventStore_Storage_Sql implements Oxy_EventStore_Storage_Interface
{
    /**
     * Database adapter
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private $_db;

    /**
     * After how many events we will do snapshot
     *
     * @var integer
     */
    private $_eventsCountToDoSnapShot;

    /**
     * Version of events
     *
     * @var integer
     */
    private $_version;

    /**
     * Database name
     *
     * Note: because of a shared connection
     * set database name
     *
     * @var string
     */
    protected $_dbName;

    /**
     * Initialize
     *
     * @return void
     */
    public function __construct(Zend_Db_Adapter_Abstract $db, $dbName)
    {
        $this->_db = $db;
        $this->_eventsCountToDoSnapShot = 200;
        $this->_version = array();
        $this->_dbName = $dbName;
    }

    /**
     * Return version
     *
     * @param Oxy_Guid $eventProviderId
     * @return integer
     */
    public function getVersion(Oxy_Guid $eventProviderId)
    {
        if(isset($this->_version[(string)$eventProviderId])){
            return $this->_version[(string)$eventProviderId];
        } else {
            return 0;
        }
    }

    /**
     * Reset version
     */
    public function resetVersion()
    {
        $this->_version = null;
    }

    /**
     * Save snapshot
     *
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     * @param integer $lastEventId
     */
    public function saveSnapShot(Oxy_EventStore_EventProvider_Interface $eventProvider, $lastEventId)
    {
        $snapShot = new Oxy_EventStore_Storage_SnapShot(
            $eventProvider->getGuid(),
            1,
            $eventProvider->createMemento()
        );
        $this->removeIsLatestFlag($eventProvider->getGuid());
        $data = array(
            'create_date' => new Zend_Db_Expr('NOW()'),
            'is_latest' => 1,
            'guid' => $eventProvider->getGuid(),
            'snapshot' => base64_encode(serialize($snapShot)),
            'at_event_id' => $lastEventId,
            'version' => $this->_version[(string)$eventProvider->getGuid()]
        );
        $this->_db->insert($this->_dbName . '.snapshots', $data);
    }

    /**
     * Get snapshot
     *
     * @param Oxy_Guid $eventProviderId
     * @return Oxy_EventStore_Storage_SnapShot_Interface|null
     */
    public function getSnapShot(Oxy_Guid $eventProviderId)
    {
        $select = $this->_db
                       ->select()
                       ->from(array('ms' => $this->_dbName . '.snapshots'))
                       ->where('`ms`.`guid` = ?', $eventProviderId)
                       ->where('`ms`.`is_latest` = ?', 1)
                       ->limit(1);
        $results = $this->_db->fetchRow($select);
        if (! isset($results['snapshot'])) {
            return null;
        }
        try {
            $object = unserialize(base64_decode($results['snapshot']));
            $this->_version[(string)$eventProviderId] = $results['version'];
        } catch (Exception $ex) {
            return null;
        }
        return $object;
    }

    /**
     * Return all events that are related
     * to $eventProviderId
     *
     * @param Oxy_Guid $eventProviderId
     *
     * @return Oxy_Domain_Event_Container_Interface
     */
    public function getAllEvents(Oxy_Guid $eventProviderId)
    {
        //$events = new Oxy_Collection('Oxy_Domain_Event_Interface');
        $events = new Oxy_Domain_Event_Container();
        $select = $this->_db
                       ->select()
                       ->from(array('me' => $this->_dbName . '.events'))
                       ->where('`me`.`aggregate_root_guid` = ?', $eventProviderId)
                       ->order('id ASC');

        $result = $this->_db->fetchAll($select);
        if (! is_array($result) || empty($result)) {
            return $events;
        }

        foreach ($result as $row) {
            $event = unserialize(base64_decode($row['event']));
            $events->addEvent(new Oxy_Guid($row['guid']), $event);
            $this->_version[(string)$eventProviderId] = $row['version'];
        }
        return $events;
    }

    /**
     * Get events count since last snapshot
     *
     * @param Oxy_Guid $eventProviderId
     * @return integer
     */
    public function getEventCountSinceLastSnapShot(Oxy_Guid $eventProviderId)
    {
        $select = $this->_db
                       ->select()
                       ->from(array('mec' => $this->_dbName . '.events_count'))
                       ->where('`mec`.`guid` = ?', $eventProviderId)
                       ->limit(1);

        $result = $this->_db->fetchRow($select);
        if (isset($result['count'])) {
            return (int)$result['count'];
        } else {
            return 0;
        }
    }

    /**
     * Get events since last snap shot
     *
     * @param Oxy_Guid $eventProviderId
     * @return Oxy_Domain_Event_Container_Interface
     */
    public function getEventsSinceLastSnapShot(Oxy_Guid $eventProviderId)
    {
        //$events = new Oxy_Collection('Oxy_Domain_Event_Interface');
        $events = new Oxy_Domain_Event_Container();
        $select = $this->_db
                       ->select()
                       ->from(array('ms' => $this->_dbName . '.snapshots'))
                       ->where('`ms`.`guid` = ?', $eventProviderId)
                       ->where('`ms`.`is_latest` = ?', 1)
                       ->limit(1);

        $results = $this->_db->fetchRow($select);

        $whereToStart = 0;
        if (isset($results['at_event_id'])) {
            $whereToStart = $results['at_event_id'];
        }
        $select = $this->_db
                       ->select()
                       ->from(array('me' => $this->_dbName . '.events'))
                       ->where('`me`.`aggregate_root_guid` = ?', $eventProviderId)
                       ->where('`me`.`id` > ?', $whereToStart);

        $result = $this->_db->fetchAll($select);

        if (! is_array($result) || empty($result)) {
            return $events;
        }
        foreach ($result as $row) {
            try {
                $event = unserialize(base64_decode($row['event']));
                $events->addEvent(new Oxy_Guid($row['guid']), $event);
                $this->_version[(string)$eventProviderId] = $row['version'];
            } catch (Exception $ex) {
                //@TODO:Log error, mark that event is broken
                // perhaps we should have few places where events
                // will be stored, because then we can try to
                // load it from other location
            }
        }
        return $events;
    }

    /**
     * Save events to database
     *
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     * @return void
     */
    public function save(Oxy_EventStore_EventProvider_Interface $eventProvider)
    {
        $changes = $eventProvider->getChanges();
        if($changes->count() > 0){
            //var_dump($changes);
            if (!$this->checkVersion($eventProvider)) {
                throw new Oxy_EventStore_Storage_ConcurrencyException('Sorry concurrency problem!');
            }
            if ($this->shouldWeDoSnapShot($eventProvider->getGuid())) {
                $lastEventId = $this->saveChanges($changes, $eventProvider->getGuid());
                $this->saveSnapShot($eventProvider, $lastEventId);
            } else {
                $this->saveChanges($changes, $eventProvider->getGuid());
            }
        }
    }

    /**
     * Check if we should do a snapshot
     *
     * @param string $uuid
     * @return boolean
     */
    private function shouldWeDoSnapShot($guid)
    {
        $select = $this->_db
                       ->select()
                       ->from(array('mec' => $this->_dbName . '.events_count'))
                       ->where('`mec`.`guid` = ?', $guid);

        $count = $this->_db->fetchRow($select);
        if (isset($count['count']) && ((int)$count['count'] === (int)$this->_eventsCountToDoSnapShot)) {
            return true;
        } elseif (isset($count['count']) && ((int)$count['count'] > (int)$this->_eventsCountToDoSnapShot)) {
            $this->updateCount(-$count['count'], $guid);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Save events to database
     *
     * @return integer
     */
    private function saveChanges(Oxy_Domain_Event_Container_ContainerInterface $events, $guid)
    {
        // @TODO: Build query by ourselfs ?
        foreach ($events->getIterator() as $key => $eventData) {
            $event = $eventData['event'];
            $eventProviderId = $eventData['eventProviderId'];
            if(isset($this->_version[(string)$eventProviderId])){
                $this->_version[(string)$eventProviderId]++;
            } else {
                $this->_version[(string)$eventProviderId] = 1;
            }
            $data = array(
                'add_date' => new Zend_Db_Expr('NOW()'),
                'aggregate_root_guid' => $guid,
                'guid' => $eventProviderId,
                'event' => base64_encode(serialize($event)),
                'version' => $this->_version[(string)$eventProviderId]
            );
            $this->_db->insert($this->_dbName . '.events', $data);
        }
        $lastId = $this->_db->lastInsertId();
        $this->updateCount(count($events), $guid);
        return (int)$lastId;
    }

    /**
     * Check for concurency
     *
     * @param Oxy_Guid $eventProviderId
     * @param integer $version
     *
     * @return boolean
     */
    private function checkVersion(Oxy_EventStore_EventProvider_Interface $eventProvider)
    {
        $select = $this->_db
                       ->select()
                       ->from(array('me' => $this->_dbName . '.events'))
                       ->where('`me`.`aggregate_root_guid` = ?', $eventProvider->getGuid())
                       ->order('id DESC')
                       ->limit(1);

        $result = $this->_db->fetchRow($select);
        //var_dump($eventProvider);
        //print "{$eventProvider->getGuid()}----(int){$result['version']} === (int){$eventProvider->getVersion()} <br/>";
        if ((int)$result['version'] === (int)$eventProvider->getVersion()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update count
     *
     * @param integer $cnt
     * @param Oxy_Guid $guid
     *
     * @return void
     */
    private function updateCount($cnt, $guid)
    {
        $select = $this->_db
                       ->select()
                       ->from(array('mec' => $this->_dbName . '.events_count'))
                       ->where('`mec`.`guid` = ?', $guid);

        $count = $this->_db->fetchRow($select);
        $data = array('count' => ((int)$count['count'] + (int)$cnt));
        if ($count) {
            $this->_db->update($this->_dbName . '.events_count', $data, "guid = '{$guid}'");
        } else {
            $data = array(
                'count' => ($count + $cnt),
                'guid' => $guid
            );
            $this->_db->insert($this->_dbName . '.events_count', $data);
        }
    }

    /**
     * Update record that says that
     * it is latest snapshot
     *
     * @return void
     */
    private function removeIsLatestFlag($guid)
    {
        $data = array(
            'is_latest' => 0
        );
        $this->_db->update($this->_dbName . '.snapshots', $data, "guid = '{$guid}'");
    }
}