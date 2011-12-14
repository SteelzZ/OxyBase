<?php
/**
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Storage;
use Oxy\EventStore\EventProvider\EventProviderInterface;

interface SnapShottingInterface
{
    /**
     * @param EventProviderInterface $eventProvider
     * @return boolean
     */
    public function isSnapShotRequired(EventProviderInterface $eventProvider);
}