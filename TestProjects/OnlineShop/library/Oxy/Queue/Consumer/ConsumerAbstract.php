<?php
/**
 * Oxy Queue consumer 
 * Base class
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Queue\Consumer;

abstract class ConsumerAbstract 
    implements ConsumerInterface
{    
    /**
     * @var array
     */
    protected $_options;
           
    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->_options = $options;
        //$this->_logger = Oxy_Log_Writer_MongoDb::factory($options['log']);
    }  
}