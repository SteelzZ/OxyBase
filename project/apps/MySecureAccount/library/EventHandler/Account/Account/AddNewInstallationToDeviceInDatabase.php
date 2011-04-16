<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage EventHandler
 * @author Tomas Bartkus <tomas@mysecuritycenter.com>
 */
class MySecureAccount_Lib_EventHandler_Account_Account_AddNewInstallationToDeviceInDatabase
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
                
            $devicesCollection->update(
                array(
                    '_id' => $event->getDeviceGuid()
                ),
                array(
                	'$push' => 
                    array(
                    	'installations' => 
                        array(
                            'installationGuid' => $event->getInstallationGuid(),
                            'date' => $event->getDate(),
                            'productGuid' => $event->getProductGuid(),
                            'productName' => $event->getProductName(),
                            'installationSettings' => $event->getInstallationSettings(),
                        )
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
