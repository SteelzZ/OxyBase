<?php
class MySecureAccount_Lib_Query_LogsInformation
    extends Oxy_Cqrs_Query_AbstractMongo
{
    const LOGS_COLLECTION = 'logs';
    
	/**
	 * Return account information
	 *   
     * @see Msc_Query_Dto_Builder_AbstractBuilder::getDto()
     */
    public function getDto(array $args = array())
    {
        $this->_initParams($args);
        try{
            $results = array();
            /*$collection = $this->_db->selectCollection(self::LOGS_COLLECTION);
            $query = array(
            );
            $results = $collection->findOne($query);  */  
                      
            return $results;
        } catch (Exception $ex){
            return null;
        } 
    }
}