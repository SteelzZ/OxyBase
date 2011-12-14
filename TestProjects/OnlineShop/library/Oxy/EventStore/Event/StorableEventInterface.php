<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Event;
use Oxy\EventStore\Event\EventInterface;
use Oxy\Guid;

interface StorableEventInterface
{
    /**
     * @return EventInterface
     */
    public function getEvent();
    
    /**
     * @return Guid
     */
    public function getProviderGuid();
}