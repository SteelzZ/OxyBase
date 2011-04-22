<?php
/**
 * Oxy Queue consumer 
 * Base class
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Queue_Consumer_ConsumerAbstract 
    implements Oxy_Queue_Consumer_ConsumerInterface
{    
    /**
     * @var array
     */
    protected $_options;
    
    /**
     * @var Oxy_Log_Writer_MongoDb
     */
    protected $_logger;
           
    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->_options = $options;
        $this->_logger = Oxy_Log_Writer_MongoDb::factory($options['log']);
    }  
}