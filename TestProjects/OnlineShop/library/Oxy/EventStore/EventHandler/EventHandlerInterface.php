<?php
/**
 * Event handler interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage EventHandler
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\EventHandler;
use Oxy\Domain\Event\EventInterface;

interface EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event);
}