<?php
interface Account_Domain_Account_AggregateRoot_Account_Product_ConfigurableInterface
{        
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Device $forDevice
     */
    public function configure(
        Account_Domain_Account_AggregateRoot_Account_Device $forDevice
    );
   
}