<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage EventHandler
 */
class MySecureAccount_Lib_EventHandler_Account_Account_UpdateProductInstallationLocationInDatabase
    extends MySecureAccount_Lib_EventHandler_Logger
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{
    
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {   
        try{
            $productsCollection = $this->_db->selectCollection(
                MySecureAccount_Lib_Query_ProductInformation::PRODUCTS_COLLECTION
            );           

            $productsCollection->update(
                array(
                    '_id' => $event->getProductGuid()
                ),
                array(
                	'$set' => array('installedOnDeviceGuid' => $event->getDeviceGuid())
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
