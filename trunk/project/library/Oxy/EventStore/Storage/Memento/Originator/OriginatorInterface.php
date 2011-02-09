<?php
/**
 * Originator interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Storage_Memento_Originator_OriginatorInterface
{
    /**
     * Create snapshot
     *
     * @return Oxy_EventStore_Storage_Memento_MementoInterface
     */
    public function createMemento();

    /**
     * Load snapshot
     *
     * @param Oxy_EventStore_Storage_Memento_MementoInterface $memento
     * @return void
     */
    public function setMemento(Oxy_EventStore_Storage_Memento_MementoInterface $memento);
}