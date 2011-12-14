<?php
/**
 * Oxy Queue interface
 *
 * @category Oxy
 * @package Oxy_Queue
 * @subpackage Oxy_Queue_Adapter
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Queue\Adapter;
use Oxy\Queue\QueueInterface;

interface AdapterInterface extends QueueInterface
{
    /**
     * Return queue id
     * 
     * @return string
     */
    public function getQueueId();
}