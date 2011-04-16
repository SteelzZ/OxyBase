<?php
class MySecureAccount_Domain_Account_ValueObject_ProductsInformationCollection 
    extends Oxy_Collection
{
    /**
     * @param array $collectionItems initial items
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'MySecureAccount_Domain_Account_ValueObject_ProductInformation'
        );
        
        $this->initializeAndAddFromArray($collectionItems);
    }
    
    public function initializeAndAddFromArray(array $data)
    {
        foreach($data as $productName => $product){
            $this->add(
                Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
                    'MySecureAccount_Domain_Account_ValueObject_ProductInformation',
                    $product
                )
            );
        }
    }
    
}