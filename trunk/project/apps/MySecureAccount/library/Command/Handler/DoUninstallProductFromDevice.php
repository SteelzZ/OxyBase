<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Account
 */
class MySecureAccount_Lib_Command_Handler_DoUninstallProductFromDevice
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
        
        $productName = new MySecureAccount_Domain_Account_ValueObject_Name($command->getProductName());
        $deviceName = new MySecureAccount_Domain_Account_ValueObject_Name($command->getDeviceName());
        
        $settings = Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
            'MySecureAccount_Domain_Account_ValueObject_Properties',
            array(
                $command->getSettings()
            )
        );
                
        $account->uninstallProductFromDevice(
            $productName,
            $deviceName,
            $settings
        );
        
        $this->_eventStoreRepository->add($account);
    }
}