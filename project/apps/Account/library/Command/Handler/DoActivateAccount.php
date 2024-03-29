<?php
/**
 * @category Account
 * @package Account_Lib
 */
class Account_Lib_Command_Handler_DoActivateAccount
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
        
        $account->confirmPrimaryEmailAddress(
            new Oxy_Guid($command->getActivationKey())
        );
        
        $this->_eventStoreRepository->add($account);
    }
}