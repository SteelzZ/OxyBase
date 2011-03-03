<?php
/**
 * Oxy Queue interface
 *
 * @category Oxy
 * @package Oxy_Queue
 * @subpackage Oxy_Queue_Message
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Queue_Message_MessageInterface
{
    /**
     * @return string
     */
    public function getId();
    
    /**
     * @return string
     */
    public function getContent();
    
    /**
     * @return string
     */
    public function getContentType();
    
    /**
     * @return string
     */
    public function getQueueId();
    
    /**
     * @return string
     */
    public function getDeliveryMode();
}