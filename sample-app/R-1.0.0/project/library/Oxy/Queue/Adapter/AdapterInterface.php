<?php
/**
 * Oxy Queue interface
 *
 * @category Oxy
 * @package Oxy_Queue
 * @subpackage Oxy_Queue_Adapter
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Queue_Adapter_AdapterInterface extends Oxy_Queue_QueueInterface
{
    /**
     * Return queue id
     * 
     * @return string
     */
    public function getQueueId();
}