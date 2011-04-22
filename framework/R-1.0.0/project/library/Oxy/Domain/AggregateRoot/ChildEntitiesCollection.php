<?php
/**
 * Entities collection
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_Domain_AggregateRoot_ChildEntitiesCollection extends Oxy_Collection
{
	/**
	 * @param array $collectionItems
	 */
	public function __construct(array $collectionItems = array())
	{
		parent::__construct(
			'Oxy_Domain_AggregateRoot_ChildEntityInterface', 
			$collectionItems
		);		
	}    
	
    /**
     * @param Oxy_Collection $collection $collection
     * @throws InvalidArgumentException when wrong type
     */
    public function addCollection(Oxy_Collection $collection)
    {
        foreach($collection as $value){
            $this->set(
                (string)$value->getGuid(), 
                $value
            );
        }
    }
}