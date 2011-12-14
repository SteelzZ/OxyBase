<?php
/**
 * Abstract DTO Builder
 *
 * @category Oxy
 * @package Oxy_Query
 * @subpackage Query
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Cqrs\Query;
use Oxy\Cqrs\Query\AbstractBuilder;

abstract class AbstractDatabase extends AbstractBuilder
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
        Zend_Db_Adapter_Abstract $db,
        $dbName,
        $frontendCache = 'Core',
        $backendCache = 'Memcached',
        $options = array()
     )
    {
        parent::__construct($frontendCache, $backendCache, $options);
        $this->_db = $db;
        $this->_dbName = $dbName;
    }
}