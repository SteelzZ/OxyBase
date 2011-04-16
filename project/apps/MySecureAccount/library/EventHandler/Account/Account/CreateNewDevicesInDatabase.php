<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage EventHandler
 * @author Tomas Bartkus <tomas@mysecuritycenter.com>
 */
class MySecureAccount_Lib_EventHandler_Account_Account_CreateNewDevicesInDatabase
    extends MySecureAccount_Lib_EventHandler_Logger 
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {                
        try{  
            foreach ($event->getDevices() as $deviceGuid => $deviceData){                  
                $deviceData['_id'] = $deviceGuid;
                $deviceData['accountGuid'] = $event->getAccountGuid();
                $deviceData['email'] = $event->getEmail();
                $deviceData['date'] = $event->getDate();
                $this->_insertData($deviceData, MySecureAccount_Lib_Query_DeviceInformation::DEVICES_COLLECTION);
            }   
        } catch (MongoCursorException $ex){
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
