<?php
class Account_Domain_Account_ValueObject_DevicesInformationCollection
    extends Oxy_Collection
{
    /**
     * @param array $collectionItems initial items
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'Account_Domain_Account_ValueObject_DeviceInformation'
        );
        
        $this->initializeAndAddFromArray($collectionItems);
    }
    
    public function initializeAndAddFromArray(array $data)
    {
        foreach($data as $deviceName => $device){
            $this->set(
                $deviceName,
                Account_Domain_Account_ValueObject_DeviceInformation::createFromArray(
                    'Account_Domain_Account_ValueObject_DeviceInformation',
                    $device
                )
            );
        }
    }
    
}