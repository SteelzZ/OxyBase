<?php
class Account_Domain_Account_ValueObject_ProductsCollection 
    extends Oxy_Collection
{    
    /**
     * @param array $collectionItems initial items
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'Account_Domain_Account_AggregateRoot_Account_Product_ConfigurableInterface',
            $collectionItems
        );
    }
    
    /**
     * @param array $params
     * 
     * @return string
     */
    public static function makeKey(array $params)
    {
        return md5(implode('', $params));        
    }
        
    /**
     * @param array $products
     * @param Account_Domain_Account_AggregateRoot_Account $account
     */
    public function createFromArray(
        array $products = array(),
        Account_Domain_Account_AggregateRoot_Account $account
    )
    {
        foreach($products as $productGuid => $productData){
            $this->set(
                $this->makeKey(array($productData['productName'], $productData['license'])), 
                new Account_Domain_Account_AggregateRoot_Account_Product(
                    new Oxy_Guid($productGuid), 
                    $account,
                    new Account_Domain_Account_ValueObject_Name($productData['productName']),
                    new Account_Domain_Account_ValueObject_License(
                        $productData['license'],
                        $productData['licenseType']   
                    )               
                )
            );
        }      
    }
    
    /**
     * @param array $products
     * @param Account_Domain_Account_AggregateRoot_Account $account
     */
    public function createAndRestoreStateFromArray(
        array $products = array(),
        Account_Domain_Account_AggregateRoot_Account $account
    )
    {
        foreach($products as $productGuid => $productMemento){
            $product = new Account_Domain_Account_AggregateRoot_Account_Product(
                new Oxy_Guid($productGuid), 
                $account,
                new Account_Domain_Account_ValueObject_Name($productMemento->getProductName()),
                new Account_Domain_Account_ValueObject_License(
                    $productMemento->getProductLicense(),
                    $productMemento->getProductLicenseType()
                )               
            );
            $product->setMemento($productMemento);
            $this->set(
                $this->makeKey(array($productMemento->getProductName(), $productMemento->getProductLicense())), 
                $product
            );
        }        
    }
    
        
    /**
     * Convert collection to array
     */
    public function toArray()
    {
        $collection = array();
        foreach ($this->_collection as $product){
            $collection[(string)$product->getGuid()] = $product->createMemento()->toArray();
        }

        return $collection;
    }
}