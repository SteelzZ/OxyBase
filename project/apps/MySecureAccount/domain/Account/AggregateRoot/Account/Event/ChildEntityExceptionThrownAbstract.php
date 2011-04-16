<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
abstract class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ChildEntityExceptionThrownAbstract
    extends MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ExceptionThrownAbstract
{    
    /**
     * @var string
     */
    protected $_childGuid;
    
	/**
     * @return string
     */
    public function getChildGuid()
    {
        return $this->_childGuid;
    }
}