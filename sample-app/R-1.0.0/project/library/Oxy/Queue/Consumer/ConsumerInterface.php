<?php
/**
 * Oxy Queue interface
 *
 * @category Oxy
 * @package Oxy_Queue
 * @subpackage Oxy_Queue_Consumer
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Queue_Consumer_ConsumerInterface
{
    /**
     * Consume messages
     */
    public function consume();
    
    /**
     * Listen for messages
     */
    public function listen();      
}