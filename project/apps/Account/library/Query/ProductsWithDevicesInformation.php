<?php
class Account_Lib_Query_ProductsWithDevicesInformation
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
                Account_Lib_Query_ProductInformation::PRODUCTS_COLLECTION
            );
            
            $query = array(
                "email" => $this->_email
            );

            $productsResults = $collection->find($query);
            
            $devicesGuids = array();
            foreach ($productsResults as $product){
                if(!empty($product['installedOnDeviceGuid'])){
                    $devicesGuids[] = $product['installedOnDeviceGuid'];
                }
            }

            $devicesCollection = $this->_db->selectCollection(
                Account_Lib_Query_DeviceInformation::DEVICES_COLLECTION
            );
                        
            $query = array(
                "email" => $this->_email,
                '_id' => array('$in' => $devicesGuids)
            );
            $devicesResults = $devicesCollection->find($query);
            
            $hash = array();
            foreach ($devicesResults as $deviceData){
                $hash[$deviceData['_id']] = $deviceData;
            }
                        
            $return = array();
            foreach ($productsResults as $key => $productData){
                $add = count($this->_tags) == 0 ? true : false;
                if(is_array($this->_tags) && count($this->_tags) > 0){
                    if(isset($productData['additionalInformation']['tags'])){
                        $result = array_intersect($this->_tags, (array)$productData['additionalInformation']['tags']);
                        $add = count($result) === count($this->_tags) ? true : false;
                    } 
                } 

                if($add){
                    $return[$key] = $productData;
                    if(!empty($productData['installedOnDeviceGuid'])){
                        $return[$key]['device'] = $hash[$productData['installedOnDeviceGuid']];
                    }
                    $return[$key]['licenseUsed'] = empty($productData['activeConfiguration']) ? false : true;
                }
            }
            
            return $return;
        } catch (Exception $ex){
            return null;
        } 
    }
}