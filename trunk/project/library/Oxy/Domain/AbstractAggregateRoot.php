<?php
/**
 * Base Aggregate Root class
 *
 * @category Oxy
 * @package Oxy_Domain
 */
abstract class Oxy_Domain_AbstractAggregateRoot
{
    /**
     * @var string
     */
    protected $_guid;

    /**
     * @var integer
     */
    protected $_version;

    /**
     * @return integer $version
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->_guid;
    }
    
    /**
     * @param string $guid
     */
    public function __construct($guid)
    {
    	$this->_guid = $guid;
    	$this->_version = 0;
    }

    /**
     * Update version
     *
     * @param integer $version
     */
    public function updateVersion($version)
    {
        $this->_version = $version;
    }
}