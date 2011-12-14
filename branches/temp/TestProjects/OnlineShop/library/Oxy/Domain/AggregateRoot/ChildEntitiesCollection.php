<?php
/**
 * Entities collection
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
namespace Oxy\Domain\AggregateRoot;
use Oxy\Collection;

class ChildEntitiesCollection extends Collection
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
     * @param Collection $collection $collection
     * @throws InvalidArgumentException when wrong type
     */
    public function addCollection(Collection $collection)
    {
        foreach($collection as $value){
            $this->set(
                (string)$value->getGuid(), 
                $value
            );
        }
    }
}