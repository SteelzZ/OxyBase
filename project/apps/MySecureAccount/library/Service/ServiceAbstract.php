<?php
class MySecureAccount_Lib_Service_ServiceAbstract
{
    /**
     * @var Oxy_Cqrs_Queue
     */
    protected $_globalQueue;
       
    /**
     * @param Oxy_Cqrs_Queue $globalQueue
     */
    public function __construct(
        Oxy_Cqrs_Queue $globalQueue
    )
    {
        $this->_globalQueue = $globalQueue;                           
    }
}