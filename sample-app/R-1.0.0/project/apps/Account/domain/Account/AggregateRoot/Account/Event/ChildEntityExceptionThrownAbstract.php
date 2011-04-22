<?php
/**
 * @category Account
 * @package Account_Domain
 * @subpackage Account
 */
abstract class Account_Domain_Account_AggregateRoot_Account_Event_ChildEntityExceptionThrownAbstract
    extends Account_Domain_Account_AggregateRoot_Account_Event_ExceptionThrownAbstract
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