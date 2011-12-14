<?php
/**
 * Events publisher interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage EventPublisher
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\EventPublisher;
use Oxy\EventStore\Event\StorableEventsCollectionInterface;

interface EventPublisherInterface
{
    /**
     * Notify listeners about events
     *
     * @param StorableEventsCollectionInterface $events
     * @return void
     */
    public function notifyListeners(
        StorableEventsCollectionInterface $events
    );
}