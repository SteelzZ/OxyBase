<?php
/**
 * @category Account
 * @package Account_Account
 */
class Account_Lib_Command_Handler_DoLogin
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
        
        $account->login(
            new Account_Domain_Account_ValueObject_EmailAddress($command->getEmailAddress()),
            new Account_Domain_Account_ValueObject_Password($command->getPassword())
        );
        
        $this->_eventStoreRepository->add($account);
    }
}