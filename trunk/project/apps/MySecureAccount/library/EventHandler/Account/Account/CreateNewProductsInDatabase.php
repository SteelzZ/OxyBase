<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage EventHandler
 */
class MySecureAccount_Lib_EventHandler_Account_Account_CreateNewProductsInDatabase
    extends MySecureAccount_Lib_EventHandler_Logger 
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {                
        try{  
            foreach ($event->getProducts() as $productGuid => $productData){                  
                $productData['_id'] = $productGuid;
                $productData['accountGuid'] = $event->getAccountGuid();
                $productData['email'] = $event->getEmail();
                $productData['date'] = $event->getDate();
                $productData['activeConfiguration'] = '';
                $productData['installedOnDeviceGuid'] = '';
                $this->_insertData($productData, MySecureAccount_Lib_Query_ProductInformation::PRODUCTS_COLLECTION);
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
