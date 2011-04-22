<?php
/**
 * Abstract DTO Builder
 *
 * @category Oxy
 * @package Oxy_Query
 * @subpackage Query
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Cqrs_Query_AbstractMongo extends Oxy_Cqrs_Query_AbstractBuilder
{
    /**
     * Database adapter
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;

    /**
     * Database name
     *
     * @var String
     */
    protected $_dbName;

    /**
     * Initialize DTO builder
     *
     * @param Zend_Db_Adapter_Abstract $db
     * @param string $dbName
     * @param string $frontendCache
     * @param string $backendCache
     * @param string $options
     */
    public function __construct(
        Mongo $db,
        $dbName,
        $frontendCache = 'Core',
        $backendCache = 'Memcached',
        $options = array()
    )
    {
        parent::__construct($frontendCache, $backendCache, $options);
        $this->_db = $db->selectDB($dbName);
        $this->_dbName = $dbName;
    }
}