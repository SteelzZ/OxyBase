<?php
/**
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
namespace Oxy\EventStore\Event;
use Oxy\Collection;

class Collection 
    extends Collection
{
    /**
     * @param array $collectionItems
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct('Oxy_EventStore_Event_EventInterface', $collectionItems);
    }
}