<?php
class Account_Lib_Service_Write_ProductsManagementService
{             
    /**
     * @var Oxy_Cqrs_Queue
     */
    protected $_globalQueue;
        
    /**
     * @param Oxy_Cqrs_Queue $globalQueue
     */
    public function __construct(
        Oxy_Cqrs_Queue $globalQueue
    )
    {
        $this->_globalQueue = $globalQueue;                              
    }
       
    /**
     * Create new products in account
     * 
     * $products:
     *  index => productName
     *  data =>
     *  - product-name
     *  - title
     *  - version
     *  - duration
     *  - quantity
     *   
     * @param string $email
     * @param array $products
     */
    public function addNewProductsInAccount($email, array $products)
    {
        $accountGuid = new Oxy_Guid();
        $normalized = array();
        foreach ($products as $productData){
            $normalized[] = array(
                $productData['name'],
                $productData['title'],
                $productData['version'],
                $productData['duration'],
                $productData['license'],
                $productData['licenseType'],
                $productData['settings'],
            );
        }
        
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoAddProductsInAccount', 
            array(
                $accountGuid,
                $email,
                $normalized
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
}