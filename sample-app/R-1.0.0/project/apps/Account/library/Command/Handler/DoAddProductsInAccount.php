<?php
class Account_Lib_Command_Handler_DoAddProductsInAccount
    extends Oxy_Cqrs_Command_Handler_EventStoreHandlerAbstract
{
    /**
     * @param Oxy_Cqrs_Command_CommandInterface $command
     */
    public function execute(Oxy_Cqrs_Command_CommandInterface $command)
    {
        $account = $this->_eventStoreRepository->getById(
            'Account_Domain_Account_AggregateRoot_Account',
            $command->getGuid(),
            $command->getRealIdentifier()
        );
                
        $productsInformationCollection = new Account_Domain_Account_ValueObject_ProductsInformationCollection(
            $command->getProducts()
        );
                
        $account->addNewProducts($productsInformationCollection);
        
        $this->_eventStoreRepository->add($account);
    }
}