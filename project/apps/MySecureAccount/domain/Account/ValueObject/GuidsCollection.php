<?php
class MySecureAccount_Domain_Account_ValueObject_GuidsCollection 
    extends Oxy_Collection
{
    /**
     * @param array $collectionItems
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'Oxy_Guid',
            $collectionItems
        );
    }
}