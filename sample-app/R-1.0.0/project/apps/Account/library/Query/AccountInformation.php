<?php
class Account_Lib_Query_AccountInformation 
    extends Oxy_Cqrs_Query_AbstractMongo
{
    const ACCOUNT_COLLECTION = 'accounts';
    
	/**
	 * Return account information
	 *   
     * @see Msc_Query_Dto_Builder_AbstractBuilder::getDto()
     */
    public function getDto(array $args = array())
    {
        $this->_initParams($args);
        try{
            $collection = $this->_db->selectCollection(self::ACCOUNT_COLLECTION);
            $query = array(
                "primaryEmail" => $this->_email
            );
            $results = $collection->findOne($query);               
            return $results;
        } catch (Exception $ex){
            return null;
        } 
    }
}