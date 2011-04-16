<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Account
 */
class MySecureAccount_Lib_Command_Handler_DoAddDevicesInAccount
    extends Oxy_Cqrs_Command_Handler_EventStoreHandlerAbstract
{
    /**
     * @param Oxy_Cqrs_Command_CommandInterface $command
     */
    public function execute(Oxy_Cqrs_Command_CommandInterface $command)
    {
        $account = $this->_eventStoreRepository->getById(
            'MySecureAccount_Domain_Account_AggregateRoot_Account',
            $command->getGuid(),
            $command->getRealIdentifier()
        );
                
        $devicesInformationCollection = new MySecureAccount_Domain_Account_ValueObject_DevicesInformationCollection(
            $command->getDevices()
        );
      
        $account->addNewDevices(
            $devicesInformationCollection
        );
        
        $this->_eventStoreRepository->add($account);
    }
}