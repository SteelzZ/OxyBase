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

abstract class AbstractMongo extends AbstractBuilder
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
     * Initialize DTO builder
     *
     * @param Mongo $db
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