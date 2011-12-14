<?php
/**
 * Entity interface
 *
 * @category Oxy
 * @package Oxy_Domain
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Domain;

interface EntityInterface
{
   /**
     * Returns unique identifier
     * 
     * @return Oxy_Guid
     */
    public function getGuid();
}