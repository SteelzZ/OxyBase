<?php
class MySecureAccount_Lib_Query_ProviderExternalData
    extends Oxy_Cqrs_Query_AbstractMongo
{    
	/**
	 * Return account information
	 *   
     * @see Msc_Query_Dto_Builder_AbstractBuilder::getDto()
     */
    public function getDto(array $args = array())
    {
        $this->_initParams($args);
        try{
            $collection = $this->_db->selectCollection(
                MySecureAccount_Lib_Query_ConfigurationInformation::CONFIGURATIONS_COLLECTION
            );
            
            $query = array(
                "productName" => $this->_productName,
                "forDeviceName" => $this->_deviceName,
                "accountEmail" => $this->_email,
                "configurationState" => 'service-configuration-finished',
            );
                        
            $results = $collection->findOne($query); 
            
            
            
            if($results){
                $extractedData = array();
                foreach ($results['results'] as $key => $data){
                    $extractedData = array_merge($extractedData, $data['data']);
                }  
                
                $return = array();
                $return['providerName'] = $this->_providerName;
                $return['externalData'] = $extractedData;
                
                return $return;
            }
            
            return null;
        } catch (Exception $ex){
            return null;
        } 
    }
}