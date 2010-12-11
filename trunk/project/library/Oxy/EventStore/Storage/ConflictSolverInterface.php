<?php
/**
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Storage_ConflictSolverInterface
{
    /**
     * @param Oxy_EventStore_EventProvider_Interface $currentEventProvider
     * @param Oxy_EventStore_EventProvider_Interface $oldeventProvider
     * 
     * @return Oxy_EventStore_EventProvider_Interface
     */
    public function solve(
        Oxy_EventStore_EventProvider_Interface $currentEventProvider,
        Oxy_EventStore_EventProvider_Interface $oldeventProvider
    );
}