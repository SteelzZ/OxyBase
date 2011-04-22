<?php
/**
 * Base command handler class
 *
 * @category Oxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Cqrs_Command_Handler_EventStoreHandlerAbstract 
    implements Oxy_Cqrs_Command_Handler_HandlerInterface
{
    /**
     * @var Oxy_Domain_Repository_EventStoreInterface
     */
    protected $_eventStoreRepository;
    
    public function __construct(Oxy_Domain_Repository_EventStoreInterface $repository)
    {
        $this->_eventStoreRepository = $repository;        
    }
}