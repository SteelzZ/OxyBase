<?php
class Account_Lib_Service_Read_ProductManagementService
{    
    /**
     * @var Account_Lib_Query_ProductInformation
     */
    protected $_productInformationDtoBuilder;
    
    /**
     * @param Account_Lib_Query_ProductInformation $productInformationDtoBuilder
     */
    public function __construct(
        Account_Lib_Query_ProductInformation $productInformationDtoBuilder
    )
    {
        $this->_productInformationDtoBuilder = $productInformationDtoBuilder;                      
    }
    
    /**
     * Return account information
     * 
     * @param string $email
     * @param string $productName
     * @param string $license
     * 
     * @return array
     */
    public function getProductInformation($email, $productName, $license)
    {
        return $this->_productInformationDtoBuilder->getDto(
            array(
                'email' => $email,
                'productName' => $productName,
                'license' => $license
            )
        ); 
    }
}