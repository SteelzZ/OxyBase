<?php
interface MySecureAccount_Domain_Account_AggregateRoot_Account_Product_ConfigurableInterface
{        
    /**
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Device $forDevice
     */
    public function configure(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Device $forDevice
    );
   
}