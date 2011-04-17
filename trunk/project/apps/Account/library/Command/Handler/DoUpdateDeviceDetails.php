<?php
/**
 * @category Account
 * @package Account_Account
 */
class Account_Lib_Command_Handler_DoUpdateDeviceDetails 
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
                
        $deviceName = new Account_Domain_Account_ValueObject_Name($command->getDeviceName());
                
        $deviceDetails = Account_Domain_Account_ValueObject_DeviceInformation::createFromArray(
            'Account_Domain_Account_ValueObject_DeviceInformation',
            $command->getNewDeviceInformation()
        );
        
        $account->updateDeviceInformation(
            $deviceName,
            $deviceDetails
        );
                
        $this->_eventStoreRepository->add($account);
    }
}