<?php
class Account_Domain_Account_ValueObject_DevicesCollection extends Oxy_Collection
{
    /**
     * @param array $collectionItems initial items
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'Account_Domain_Account_AggregateRoot_Account_Device',
            $collectionItems
        );
    }
    
	/**
     * @param array $products
     * @param Account_Domain_Account_AggregateRoot_Account $account
     */
    public function createFromArray(
        array $devices = array(),
        Account_Domain_Account_AggregateRoot_Account $account
    )
    {
        foreach($devices as $deviceGuid => $deviceData){
            $this->set(
                $deviceData['name'], 
                new Account_Domain_Account_AggregateRoot_Account_Device(
                    new Oxy_Guid($deviceGuid), 
                    $account,
                    new Account_Domain_Account_ValueObject_Name(
                        $deviceData['name']
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
        array $devices = array(),
        Account_Domain_Account_AggregateRoot_Account $account
    )
    {
        foreach($devices as $deviceGuid => $deviceMemento){
            $device = new Account_Domain_Account_AggregateRoot_Account_Device(
                new Oxy_Guid($deviceGuid), 
                $account,
                new Account_Domain_Account_ValueObject_Name(
                    $deviceMemento->getDeviceName()
                )
            );
            $device->setMemento($deviceMemento);
            $this->set(
                $deviceMemento->getDeviceName(), 
                $device
            );
        }        
    }
    
        
    /**
     * Convert collection to array
     */
    public function toArray()
    {
        $collection = array();
        foreach ($this->_collection as $device){
            $collection[(string)$device->getGuid()] = $device->createMemento()->toArray();
        }

        return $collection;
    }
}