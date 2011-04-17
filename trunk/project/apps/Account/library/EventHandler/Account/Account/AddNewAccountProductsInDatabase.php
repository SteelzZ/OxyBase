<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage EventHandler
 */
class Account_Lib_EventHandler_Account_Account_AddNewAccountProductsInDatabase 
    extends Account_Lib_EventHandler_Logger 
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{    
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     * 
     *  'accountGuid' => (string)$this->_guid,
     *  'email' => (string)$this->_primaryEmail,
     *	'products' => $addedProducts,
     *  'date' => date('Y-m-d H:i:s'),
     *  'instances' => $instances
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {   
        try{
            $accountsCollection = $this->_db->selectCollection(
                Account_Lib_Query_AccountInformation::ACCOUNT_COLLECTION
            );          
            foreach ($event->getProducts() as $productGuid => $productData){  
                $accountsCollection->update(
                    array(
                        '_id' => $event->getAccountGuid()
                    ),
                    array(
                    	'$push' => array('products' => array_merge(array('productGuid' => $productGuid),$productData))
                    )
                );   
            }   
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
