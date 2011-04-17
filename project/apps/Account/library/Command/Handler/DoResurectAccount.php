<?php
/**
 * @category Account
 * @package Account_Account
 */
class Account_Lib_Command_Handler_DoResurectAccount
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
        
        $primaryEmailAddress = new Account_Domain_Account_ValueObject_EmailAddress(
            $command->getEmailAddress()
        );
        
        $password = new Account_Domain_Account_ValueObject_Password(
            $command->getPassword()
        );
        $passwordAgain = new Account_Domain_Account_ValueObject_Password(
            $command->getPasswordAgain()
        );
        
        $ownerPersonalInformation = Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
            'Account_Domain_Account_ValueObject_PersonalInformation',
            $command->getPersonalInformation()
        );
        
        $ownerDeliveryInformation = Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
            'Account_Domain_Account_ValueObject_DeliveryInformation',
            $command->getDeliveryInformation()
        );
        
        $settings = Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
            'Account_Domain_Account_ValueObject_Properties',
            $command->getSettings()
        );
        
        $account->resurect(
            $primaryEmailAddress, 
            $password,
            $passwordAgain,
            $ownerPersonalInformation,
            $ownerDeliveryInformation,
            $settings
        );
        
        $this->_eventStoreRepository->add($account);
    }
}