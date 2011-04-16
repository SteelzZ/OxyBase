<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Account
 */
class MySecureAccount_Lib_Command_Handler_DoUpdatePersonalDetails
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
                
        $ownerPersonalInformation = Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
            'MySecureAccount_Domain_Account_ValueObject_PersonalInformation',
            $command->getNewPersonalInformation()
        );
        
        $account->changeOwnerPersonalInformation(
            $ownerPersonalInformation
        );
        
        $this->_eventStoreRepository->add($account);
    }
}