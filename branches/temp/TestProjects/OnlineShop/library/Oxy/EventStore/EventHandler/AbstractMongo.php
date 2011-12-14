<?php
/**
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage EventHandler
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\EventHandler;

abstract class Oxy_EventStore_EventHandler_AbstractMongo
{
    /**
     * Database adapter
     *
     * @var Mongo
     */
    protected $_db;

    /**
     * Database name
     *
     * @var String
     */
    protected $_dbName;

    /**
     * @param Mongo $db
     * @param string $dbName
     */
    public function __construct(Mongo $db, $dbName)
    {
        $this->_db = $db->selectDB($dbName);
        $this->_dbName = $dbName;
    }
    
    /**
     * @param array $data
     * @param string $collection
     */
    protected function _insertData($data, $collection)
    {
        $accountsCollection = $this->_db->selectCollection($collection);
        $accountsCollection->insert($data, array("safe" => true));
    }
}