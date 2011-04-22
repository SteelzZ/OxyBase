<?php
/**
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_EventStore_Event_Collection 
    extends Oxy_Collection
{
    /**
     * @param array $collectionItems
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct('Oxy_EventStore_Event_EventInterface', $collectionItems);
    }
}