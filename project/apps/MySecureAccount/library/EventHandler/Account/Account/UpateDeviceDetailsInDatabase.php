<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage EventHandler
 */
class MySecureAccount_Lib_EventHandler_Account_Account_UpateDeviceDetailsInDatabase
    extends MySecureAccount_Lib_EventHandler_Logger
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{
    
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {   
        try{
            $devicesCollection = $this->_db->selectCollection(
            	MySecureAccount_Lib_Query_DeviceInformation::DEVICES_COLLECTION
            );         
            $accountsCollection = $this->_db->selectCollection(
            	MySecureAccount_Lib_Query_AccountInformation::ACCOUNT_COLLECTION
            );         

            $newDeviceData = $event->getNewDetails();
            $devicesCollection->update(
                array(
                    '_id' => $event->getDeviceGuid()
                ),
                array(
                	'$set' => array(
                        'operatingSystem' => $newDeviceData['operatingSystem'],
                        'title' => $newDeviceData['title']
                     )
                )
            );  

            $accountsCollection->update(
                array(
                    '_id' => $event->getAccountGuid(),
                    'devices.deviceGuid' => $event->getDeviceGuid()
                ),
                array(
                    '$set' => array(
                        'devices.$.operatingSystem' => $newDeviceData['operatingSystem'],
                        'devices.$.title' => $newDeviceData['title']
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
