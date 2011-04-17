<?php
class Account_Domain_Account_ValueObject_InstallationsCollection 
    extends Oxy_Collection
{
    /**
     * @param array $collectionItems
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'Account_Domain_Account_ValueObject_Installation',
            $collectionItems
        );
    }
    
    /**
     * @param array $products
     * @param Account_Domain_Account_AggregateRoot_Account $account
     */
    public function createFromArray(
        array $installations = array()
    )
    {
        foreach($installations as $productName => $installation){
            $this->set(
                $productName, 
                new Account_Domain_Account_ValueObject_Installation(
                    $installation['guid'],
                    $installation['configurationGuid'],
                    $installation['productGuid'],
                    $installation['productLicense'],
                    $installation['productLicenseType'],
                    $installation['date']
                )
            );
        }        
    }
    
    /**
     * @see Oxy_Collection::toArray()
     */
    public function toArray()
    {
        $results = array();
        foreach ($this->_collection as $productName => $installation){
            $results[$productName] = $installation->toArray();
        }
        
        return $results;
    }
}