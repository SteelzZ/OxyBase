<?php
/**
 * SnapShot storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Storage\SnapShot;
use Oxy\EventStore\Storage\Memento\MementoInterface;
use Oxy\Guid;

interface SnapShotInterface
{
    /**
     * Return memento
     *
     * @return MementoInterface
     */
    public function getMemento();

    /**
     * Return event provider GUID
     *
     * @return Guid
     */
    public function getEventProviderGuid();

    /**
     * Return version
     *
     * @return integer
     */
    public function getVersion();
}