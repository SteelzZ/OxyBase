<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage EventHandler
 */
class MySecureAccount_Lib_EventHandler_Account_Account_UpdateAccountDetailsInDatabase
    extends MySecureAccount_Lib_EventHandler_Logger
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {   
        try{
            $accountsCollection = $this->_db->selectCollection(
                MySecureAccount_Lib_Query_AccountInformation::ACCOUNT_COLLECTION
            );    
      
            $accountsCollection->update(
                array(
                    '_id' => $event->getAccountGuid()
                ),
                array(
                	'$set' => array(
                		'personalInformation' => $event->getPersonalInformation(),
                		'deliveryInformation' => $event->getDeliveryInformation(),
                		'state' => $event->getState(),
                		'settings' => $event->getSettings(),
                		'password' => $event->getEncodedPassword(),
                		'activationKey' => $event->getEmailActivationKey(),
                		'lastResurectDate' => $event->getDate(),
                    )
                ),
                array("safe" => true)
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
