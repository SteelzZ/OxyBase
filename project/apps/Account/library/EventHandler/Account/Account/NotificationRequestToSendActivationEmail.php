<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage EventHandler
 */
class Account_Lib_EventHandler_Account_Account_NotificationRequestToSendActivationEmail
    extends Account_Lib_EventHandler_Logger 
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{    
    /**
     * @param Mongo $db
     * @param string $dbName
     * @param Zend_Soap_Client $notificationSoapClient
     */
    public function __construct(
        Mongo $db, 
        $dbName
    )
    {
        $this->_db = $db->selectDB($dbName);
        $this->_dbName = $dbName;
    }
    
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {                
         
    } 
}
