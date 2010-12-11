<?php
/**
 * Originator interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 * @author <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Storage_Memento_Originator_Interface
{
    /**
     * Create snapshot
     *
     * @return Oxy_EventStore_Storage_Memento_Interface
     */
    public function createMemento();

    /**
     * Load snapshot
     *
     * @param Oxy_EventStore_Storage_Memento_Interface $memento
     * @return void
     */
    public function setMemento(Oxy_EventStore_Storage_Memento_Interface $memento);
}