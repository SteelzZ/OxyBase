<?php
class MySecureAccount_Lib_Query_DeviceInformation
    extends Oxy_Cqrs_Query_AbstractMongo
{
    const DEVICES_COLLECTION = 'devices';
    
	/**
	 * Return account information
	 *   
     * @see Msc_Query_Dto_Builder_AbstractBuilder::getDto()
     */
    public function getDto(array $args = array())
    {
        $this->_initParams($args);
        try{
            $collection = $this->_db->selectCollection(self::DEVICES_COLLECTION);
            $query = array(
                "email" => $this->_email,
                "name" => $this->_deviceName,
            );
                                                        
            $results = $collection->findOne($query);      

            return $results;
        } catch (Exception $ex){
            return null;
        } 
    }
}