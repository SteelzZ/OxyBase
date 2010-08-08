<?php
/**
 * Orginator interface
 * Memento pattern
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_EventStore_Storage_Memento_Orginator_Interface
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