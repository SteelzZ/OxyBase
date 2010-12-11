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
     * Returns unique identifier
     * 
     * @return Oxy_Guid
     */
    public function getGuid();
}