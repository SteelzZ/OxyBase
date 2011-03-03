<?php
/**
 * Oxy Queue interface
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Queue_QueueInterface
{
    /**
     * Add new message to bus
     * 
     * @param Oxy_Queue_Message_MessageInterface $message
     */
    public function add(Oxy_Queue_Message_MessageInterface $message);
    
    /**
     * Commit message
     */
    public function commit();
    
    /**
     * Rollback messages
     */
    public function rollback();

    /**
     * Get messages from bus
     * 
     * @return Oxy_Queue_Message_MessageInterface
     */
    public function get();

    /**
     * Remove message
     * 
     * @param Oxy_Queue_Message_MessageInterface $message
     */
    public function remove(Oxy_Queue_Message_MessageInterface $message);

    /**
     * Clean up
     */
    public function purge();
}