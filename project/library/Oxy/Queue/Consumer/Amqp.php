<?php
/**
 * Oxy Queue worker
 *
 * @category Oxy
 * @package Oxy_Queue
 * @subpackage Oxy_Queue_Consumer
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_Queue_Consumer_Amqp extends Oxy_Queue_Consumer_ConsumerAbstract
{
    /**
     * @var Oxy_Queue_Adapter_Rabbitmq
     */
    protected $_queue;
    
    /**
     * Yes this is wrong:
     * Oxy_Queue_Adapter_Rabbitmq
     * 
     * We should have interface here
     * But for now ...
     * 
     * @param Oxy_Queue_Adapter_Rabbitmq $queue
     * @param array $options
     */
    public function __construct(Oxy_Queue_Adapter_Rabbitmq $queueAdapter, array $options = array())
    {
        parent::__construct($options);
        $this->_queue = $queueAdapter;                        
    }
	/* (non-PHPdoc)
     * @see Oxy_Queue_Consumer_ConsumerInterface::consume()
     */
    public function consume ()
    {
    }

	/* (non-PHPdoc)
     * @see Oxy_Queue_Consumer_ConsumerInterface::listen()
     */
    public function listen ()
    {
    }
}