<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Event;
use Oxy\EventStore\Event\StorableEventInterface;

interface StorableEventsCollectionInterface
{
    /**
     * Set collection items
     *
     * @param array $collectionItems
     */
    public function addEvents(array $collectionItems);

    /**
     * @param StorableEventInterface $event
     */
    public function addEvent(StorableEventInterface $event);
}