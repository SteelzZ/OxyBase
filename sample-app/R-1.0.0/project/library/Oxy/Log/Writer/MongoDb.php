<?php
class Oxy_Log_Writer_MongoDb extends Zend_Log_Writer_Abstract
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
     * Database name
     *
     * @var String
     */
    protected $_collection;

    /**
     * @param Mongo $db
     * @param string $dbName
     * @param string $collection
     */
    public function __construct(Mongo $db, $dbName, $collection)
    {
        $this->_db = $db->selectDB($dbName);
        $this->_dbName = $dbName;
        $this->_collection = $this->_db->selectCollection($collection);
    }

    /**
     * Create a new instance of Oxy_Log_Writer_Db
     * 
     * @param  array|Zend_Config $config
     * @return Oxy_Log_Writer_MongoDb
     * @throws Zend_Log_Exception
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(
            array(
                'db'        => null, 
                'dbName'     => null, 
                'collection' => null,
            ), 
            $config
        );

        return new self(
            $config['db'],
            $config['dbName'],
            $config['collection']
        );
    }

    /**
     * Formatting is not possible on this writer
     */
    public function setFormatter($formatter)
    {
        throw new Oxy_Log_Exception(get_class() . ' does not support formatting');
    }

    /**
     * Remove reference to database adapter
     *
     * @return void
     */
    public function shutdown()
    {
        $this->_db = null;
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        if ($this->_db === null) {
            throw new Oxy_Log_Exception('Database adapter is null');
        }
        
        $dataToInsert = $event;
        
        $dataToInsert = array(
            'date' => date('Y-m-d H:i:s'),
            'event' => $event
        );
        
        $this->_collection->insert($dataToInsert, array('safe' => false));        
    }
}
