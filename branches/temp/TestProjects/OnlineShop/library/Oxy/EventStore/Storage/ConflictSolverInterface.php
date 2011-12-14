<?php
/**
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Storage;
use Oxy\EventStore\EventProvider\EventProviderInterface;

interface ConflictSolverInterface
{
    /**
     * @param EventProviderInterface $currentEventProvider
     * @param EventProviderInterface $oldEventProvider
     * 
     * @return EventProviderInterface
     */
    public function solve(
        EventProviderInterface $currentEventProvider,
        EventProviderInterface $oldEventProvider
    );
}