<?php
/**
 * Base command handler class
 *
 * @category Oxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Cqrs\Command\Handler;
use Oxy\Cqrs\Command\Handler\HandlerInterface;
use Oxy\Domain\Repository\EventStoreInterface;

abstract class EventStoreHandlerAbstract 
    implements HandlerInterface
{
    /**
     * @var EventStoreInterface
     */
    protected $_eventStoreRepository;
    
    public function __construct(EventStoreInterface $repository)
    {
        $this->_eventStoreRepository = $repository;        
    }
}