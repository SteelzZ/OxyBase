<?php
/**
 * Event storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Storage;
use Oxy\eventStore\Storage\SnapShotStorage\SnapShotStorageInterface;
use Oxy\EventStore\Event\StorableEventsCollectionInterface;
use Oxy\EventStore\EventProvider\EventProviderInterface;

interface StorageInterface extends SnapShotStorageInterface
{
    /**
     * Get all events for event provider
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return StorableEventsCollectionInterface
     */
    public function getAllEvents(Oxy_Guid $eventProviderGuid);

    /**
     * Get all events since last snapshot
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return StorableEventsCollectionInterface
     */
    public function getEventsSinceLastSnapShot(Oxy_Guid $eventProviderGuid);

    /**
     * Get events count since last snapshot
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return integer
     */
    public function getEventCountSinceLastSnapShot(Oxy_Guid $eventProviderGuid);

    /**
     * Save event provider events
     *
     * @param EventProviderInterface $eventProvider
     * @return void
     */
    public function save(EventProviderInterface $eventProvider);

    /**
     * Return version
     * 
     * @return integer
     */
    public function getVersion();
}