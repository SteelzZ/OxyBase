<?php
class MySecureAccount_Domain_Account_ValueObject_DevicesInformationCollection
    extends Oxy_Collection
{
    /**
     * @param array $collectionItems initial items
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'MySecureAccount_Domain_Account_ValueObject_DeviceInformation'
        );
        
        $this->initializeAndAddFromArray($collectionItems);
    }
    
    public function initializeAndAddFromArray(array $data)
    {
        foreach($data as $deviceName => $device){
            $this->set(
                $deviceName,
                MySecureAccount_Domain_Account_ValueObject_DeviceInformation::createFromArray(
                    'MySecureAccount_Domain_Account_ValueObject_DeviceInformation',
                    $device
                )
            );
        }
    }
    
}