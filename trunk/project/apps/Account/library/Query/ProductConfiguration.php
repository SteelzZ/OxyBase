<?php
class Account_Lib_Query_ProductConfiguration
    extends Oxy_Cqrs_Query_AbstractMongo
{    
    /**
     * @var ApplicationContainer
     */
    private $_diContainer;
    
    /**
     * @param Mongo $db
     * @param string $dbName
     * @param sfServiceContainer $diContainer
     */
    public function __construct(
        Mongo $db, 
        $dbName, 
        sfServiceContainer $diContainer
    )
    {
        parent::__construct($db, $dbName);
        $this->_diContainer = $diContainer;
    }
        
	/**
	 * Return account information
	 *   
     * @see Msc_Query_Dto_Builder_AbstractBuilder::getDto()
     */
    public function getDto(array $args = array())
    {
        $this->_initParams($args);
        try{
            // @todo: add into Mongo
            $configurations = array(
                Account_Lib_Query_ProductInformation::MY_MOBILE_THEFT_PROTECTION_PRODUCT =>
                array(
                    'product' => Account_Lib_Query_ProductInformation::MY_MOBILE_THEFT_PROTECTION_PRODUCT,
                    'flow' => 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateUserProfileState',
                    'actions' => array(
                        'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateUserProfileState' =>
                            array(
                                'name' => 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateUserProfileState',
                                'requiredData' => 'mySecureAccountLibQueryPropertiesRequestToCreateUserProfile'
                            ),
                        'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToGenerateAuthCodeState' => 
                            array(
                                'name' => 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToGenerateAuthCodeState',
                                'requiredData' => 'mySecureAccountLibQueryPropertiesRequestToGenerateAuthCode'
                            ),
                        'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateDeviceState' =>
                            array(
                                'name' => 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateDeviceState',
                                'requiredData' => 'mySecureAccountLibQueryPropertiesRequestToCreateDevice',
                            )
                    )
                ),
                Account_Lib_Query_ProductInformation::MY_THEFT_PROTECTION_PRODUCT =>
                array(
                    'product' => Account_Lib_Query_ProductInformation::MY_THEFT_PROTECTION_PRODUCT,
                    'flow' => 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateUserProfileState',
                    'actions' => array(
                        'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateUserProfileState' =>
                            array(
                                'name' => 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateUserProfileState',
                                'requiredData' => 'mySecureAccountLibQueryPropertiesRequestToCreateUserProfile'
                            ),
                        'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToGenerateAuthCodeState' => 
                            array(
                                'name' => 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToGenerateAuthCodeState',
                                'requiredData' => 'mySecureAccountLibQueryPropertiesRequestToGenerateAuthCode'
                            ),
                        'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateDeviceState' =>
                            array(
                                'name' => 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Ygib_RequestToCreateDeviceState',
                                'requiredData' => 'mySecureAccountLibQueryPropertiesRequestToCreateDevice',
                            )
                    )
                ),
            );
            $properties = array();
            if(isset($configurations[$this->_productName])){

                if(isset($this->_action)){
                    $dtoBuilderServiceName = $configurations[$this->_productName]['actions'][$this->_action]['requiredData'];
                    $flow = $this->_action;
                } else {
                    $dtoBuilderServiceName = $configurations[$this->_productName]['actions'][$configurations[$this->_productName]['flow']]['requiredData'];
                    $flow = $configurations[$this->_productName]['flow'];
                }
    
                if(is_string($dtoBuilderServiceName) && !empty($dtoBuilderServiceName)){
                    $properties = $this->_diContainer->getService($dtoBuilderServiceName)->getDto($args);  
                    $properties['flow'] = $flow;
                    $properties['_serviceUsed'] = $dtoBuilderServiceName;
                } else {
                    return null; 
                }
            } else {
                $dtoBuilderServiceName = 'mySecureAccountLibQueryPropertiesRequestToConfigureLocally';
                $properties = $this->_diContainer->getService($dtoBuilderServiceName)->getDto($args);  
                $properties['flow'] = 'Account_Domain_Account_AggregateRoot_ServiceConfiguration_Local_RequestToConfigureLocally';
            }
            
            return $properties;
        } catch (Exception $ex){
            return null;
        } 
    }
}