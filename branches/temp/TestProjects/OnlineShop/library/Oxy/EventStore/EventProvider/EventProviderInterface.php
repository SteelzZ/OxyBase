<?php
/**
 * Event provider interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage EventProvider
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\EventProvider;
use Oxy\EventStore\Storage\Memento\Originator\OriginatorInterface;
use Oxy\EventStore\Event\StorableEventsCollectionInterface;

interface EventProviderInterface
    extends OriginatorInterface
{
    /**
     * Load events
     *
     * @param StorableEventsCollectionInterface $domainEvents
     * 
     * @return void
     */
    public function loadEvents(StorableEventsCollectionInterface $domainEvents);

    /**
     * Update version
     *
     * @param Integer $version
     * @return void
     */
    public function updateVersion($version);

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion();

    /**
     * Get changes
     *
     * @return StorableEventsCollectionInterface
     */
    public function getChanges();
    
    /**
     * Return event provider name
     * 
     * @return string
     */
    public function getName();
    
    /**
     * Return real identifier
     * 
     * @return string
     */
    public function getRealIdentifier();
}