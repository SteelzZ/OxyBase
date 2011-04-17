<?php
class Account_Lib_Query_DevicesWithProductsInformation
    extends Oxy_Cqrs_Query_AbstractMongo
{    
    const OK = 'OK';
    const PENDING = 'PENDING';
    const FAILED = 'FAILED';
    
	/**
	 * Return account information
	 *   
     * @see Msc_Query_Dto_Builder_AbstractBuilder::getDto()
     */
    public function getDto(array $args = array())
    {
        $this->_initParams($args);
        try{
            
            $devicesCollection = $this->_db->selectCollection(
                Account_Lib_Query_DeviceInformation::DEVICES_COLLECTION
            );
                        
            $query = array(
                "email" => $this->_email,
            );
            $devicesResults = $devicesCollection->find($query);
                                    
            $productGuids = array();
            if($devicesResults){
                foreach ($devicesResults as $device){
                    if(isset($device['installations'])){
                        foreach ($device['installations'] as $key => $installation){
                            $productGuids[] = $installation['productGuid'];
                        }
                    }
                }
            }   
                   
            $collection = $this->_db->selectCollection(
                Account_Lib_Query_ProductInformation::PRODUCTS_COLLECTION
            );
                        
            $query = array(
                '_id' => array('$in' => $productGuids)
            );
            
            $productsResults = $collection->find($query);
            
            $configurationErrosCollection = $this->_db->selectCollection(
                Account_Lib_Query_ConfigurationErrorsInformation::CONFIG_ERRORS_COLLECTION
            );
            
            $hash = array();
            $configsGuids = array();
            foreach ($productsResults as $productData){
                $hash[$productData['_id']] = $productData;
                $configsGuids[] = $productData['activeConfiguration'];
            }
            
            $query = array(
                '_id' => array('$in' => $configsGuids)
            );
            
            $configResults = $configurationErrosCollection->find($query);
            
            $configHash = array();
            foreach($configResults as $configResult){
                $configHash[$configResult['_id']] = $configResult;
            }
                        
            $configurationsCollection = $this->_db->selectCollection(
                Account_Lib_Query_ConfigurationInformation::CONFIGURATIONS_COLLECTION
            );
            
            $configurations = $configurationsCollection->find($query);
            
            $configurationsHash = array();
            $configurationResults = array();
            foreach($configurations as $configuration){
                $configurationsHash[$configuration['_id']] = $configuration;
                foreach ($configuration['results'] as $configurationResult){
                    $configurationResults[$configuration['_id']][array_pop(explode('_', $configurationResult['stateClassName']))] = $configurationResult['data'];
                }
            }
                        
            $return = array();
            foreach ($devicesResults as $key => $deviceData){
                $add = count($this->_tags) == 0 ? true : false;
                if(is_array($this->_tags) && count($this->_tags) > 0){
                    if(isset($deviceData['settings']['tags'])){
                        $result = array_intersect($this->_tags, (array)$deviceData['settings']['tags']);
                        $add = count($result) === count($this->_tags) ? true : false;
                    } 
                } 
                
                if($add){
                    if(isset($deviceData['installations'])){
                        $instllations = array();
                        foreach ($deviceData['installations'] as $installationKey => $installation){
                            $instllations[$installationKey] = $installation;
                            $instllations[$installationKey]['productData'] = $hash[$installation['productGuid']];
                            if(
                                isset($configHash[$instllations[$installationKey]['productData']['activeConfiguration']])
                                || $configurationsHash[$instllations[$installationKey]['productData']['activeConfiguration']]['configurationState'] === Account_Domain_Account_ValueObject_State::SERVICE_CONFIGURATION_STARTED
                            ){
                                $status = self::FAILED;
                                $instllations[$installationKey]['results']['results'] = array();
                                if(isset($configHash[$instllations[$installationKey]['productData']['activeConfiguration']])){
                                    $instllations[$installationKey]['results']['errors'] = $configHash[$instllations[$installationKey]['productData']['activeConfiguration']]['results'];
                                } else {
                                    $instllations[$installationKey]['results']['errors'] = array();
                                }
                            } else {
                                $status = self::OK;
                                $instllations[$installationKey]['results']['errors'] = array();
                                $instllations[$installationKey]['results']['results'] = $configurationResults[$instllations[$installationKey]['productData']['activeConfiguration']];
                            }
                            $instllations[$installationKey]['results']['status'] = $status;
                        }                      
                    }  else {
                        $instllations = array();
                    }
                    $return[$key] = $deviceData;
                    $return[$key]['installations'] = $instllations;
                }
            }
            
            return $return;
        } catch (Exception $ex){
            return null;
        } 
    }
}