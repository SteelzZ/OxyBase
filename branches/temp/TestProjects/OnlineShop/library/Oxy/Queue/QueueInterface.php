<?php
/**
 * Oxy Queue interface
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Queue;

interface QueueInterface
{
    /**
     * Add new message to bus
     * 
     * @param Message\MessageInterface $message
     */
    public function add(Message\MessageInterface $message);
    
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
     * @return Message\MessageInterface
     */
    public function get();

    /**
     * Remove message
     * 
     * @param Message\MessageInterface $message
     */
    public function remove(Message\MessageInterface $message);

    /**
     * Clean up
     */
    public function purge();
}