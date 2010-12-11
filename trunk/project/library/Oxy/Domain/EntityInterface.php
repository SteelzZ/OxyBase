<?php
/**
 * Entity interface
 *
 * @category Oxy
 * @package Oxy_Domain
 */
interface Oxy_Domain_EntityInterface
{
    /**
     * Returns the current version number of the entity, or null if the entity is newly created. This
     * version must reflect the version number of the entity on which changes are applied.
     * 
     * Each time the entity is modified and stored in a repository, the version number must be increased by
     * at least 1. This version number can be used by optimistic locking strategies and detection of conflicting
     * concurrent modification.
     * 
     * Typically the sequence number of the last committed event on this entity is used as version number.
     * Return the current version number of this entity, or null if no events were ever committed
     * 
     * @return integer 
     */
    public function getVersion();
    
    /**
     * Returns unique identifier
     * 
     * @return Oxy_Guid
     */
    public function getGuid();
}