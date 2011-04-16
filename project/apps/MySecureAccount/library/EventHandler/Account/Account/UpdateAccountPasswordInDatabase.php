<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage EventHandler
 * @author Tomas Bartkus <tomas@mysecuritycenter.com>
 */
class MySecureAccount_Lib_EventHandler_Account_Account_UpdateAccountPasswordInDatabase
    extends MySecureAccount_Lib_EventHandler_Logger
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{
    
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {   
        try{
            $accountsCollection = $this->_db->selectCollection('accounts');     

            $generatedPassword = '';
            // Support two different events
            if(method_exists($event, 'getPassword')){
                $generatedPassword = $event->getPassword();
            }
            
            $accountsCollection->update(
                array(
                    '_id' => $event->getAccountGuid()
                ),
                array(
                	'$set' => array(
                		'password' => $event->getEncodedPassword(),
                		'generatedPassword' => $generatedPassword,
                    )
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
