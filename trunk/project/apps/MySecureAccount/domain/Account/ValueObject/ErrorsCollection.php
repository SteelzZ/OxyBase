<?php
class MySecureAccount_Domain_Account_ValueObject_ErrorsCollection extends Oxy_Collection
{
    /**
     * @param array $collectionItems initial items
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'MySecureAccount_Domain_Account_ValueObject_Error'
        );
        
        foreach ($collectionItems as $code => $error){
            $this->set($code, $error);
        }
    }
}