<?php
class MySecureAccount_Lib_Query_ProductInformation
    extends Oxy_Cqrs_Query_AbstractMongo
{
    const PRODUCTS_COLLECTION = 'products';
    
	/**
	 * Return account information
	 *   
     * @see Msc_Query_Dto_Builder_AbstractBuilder::getDto()
     */
    public function getDto(array $args = array())
    {
        $this->_initParams($args);
        try{
            $collection = $this->_db->selectCollection(self::PRODUCTS_COLLECTION);
            $query = array(
                "email" => $this->_email,
                "productName" => $this->_productName
            );
            
            if(isset($this->_license)){
                $query['license'] = $this->_license;
            }
            
            $results = $collection->findOne($query);
            return $results;
        } catch (Exception $ex){
            return null;
        } 
    }
}