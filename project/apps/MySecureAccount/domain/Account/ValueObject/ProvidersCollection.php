<?php
class MySecureAccount_Domain_Account_ValueObject_ProvidersCollection 
    extends Oxy_Collection
{
    /**
     * @var array
     */
    private $_mapByName;
    
    /**
     * @param array $collectionItems initial items
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct(
            'MySecureAccount_Domain_Account_AggregateRoot_Service_Provider_ProviderInterface',
            $collectionItems
        );
    }
    
	/**
     * @param array $providers
     * @param MySecureAccount_Domain_Account_AggregateRoot_Service $serviceBroker
     */
    public function createAndRestoreStateFromArray(
        array $providers = array(),
        MySecureAccount_Domain_Account_AggregateRoot_Service $service
    )
    {
        foreach($providers as $providerGuid => $providerMemento){
            $provider = MySecureAccount_Domain_Account_AggregateRoot_Service_Provider_ProviderAbstract::factoryByName(
                new MySecureAccount_Domain_Account_ValueObject_Name($providerMemento->getProviderName()), 
                new Oxy_Guid($providerGuid), 
                $service
            );
            
            $provider->setMemento($providerMemento);
            $this->set(
                $providerMemento->getProviderName(), 
                $provider
            );
        }        
    }
            
    /**
     * Convert collection to array
     */
    public function toArray()
    {
        $collection = array();
        foreach ($this->_collection as $providerName => $provider){
            $collection[(string)$provider->getGuid()] = $provider->createMemento()->toArray();
        }

        return $collection;
    }
}