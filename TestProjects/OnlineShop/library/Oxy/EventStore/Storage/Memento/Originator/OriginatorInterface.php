<?php
/**
 * Originator interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Storage\Memento\Originator;
use Oxy\EventStore\Storage\Memento\MementoInterface;

interface OriginatorInterface
{
    /**
     * Create snapshot
     *
     * @return MementoInterface
     */
    public function createMemento();

    /**
     * Load snapshot
     *
     * @param MementoInterface $memento
     * @return void
     */
    public function setMemento(MementoInterface $memento);
}