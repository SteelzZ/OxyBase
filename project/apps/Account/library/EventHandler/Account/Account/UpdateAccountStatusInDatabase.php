<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage EventHandler
 */
class Account_Lib_EventHandler_Account_Account_UpdateAccountStatusInDatabase 
    extends Account_Lib_EventHandler_Logger
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{
    
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {   
        try{
            $accountsCollection = $this->_db->selectCollection(
                Account_Lib_Query_AccountInformation::ACCOUNT_COLLECTION
            );            
            $accountsCollection->update(
                array(
                    '_id' => $event->getAccountGuid()
                ),
                array(
                	'$set' => array('state' => $event->getState())
                )
            );      
        } catch (Exception $ex){
            $this->_log(
                get_class($this), 
                array(
                    'message' => $ex->getMessage(),
                    'trace' => $ex->getTrace(),
                    'code' => $ex->getCode(),
                ), 
                parent::ERROR_LEVEL_EXCEPTION
            );
        }   
    } 
}
