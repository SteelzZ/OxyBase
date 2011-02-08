<?php
/**
 * Entities collection
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_Domain_Entity_EventSourcedEntitiesCollection extends Oxy_Collection
{
	/**
	 * @param array $collectionItems
	 */
	public function __construct(array $collectionItems = array())
	{
		parent::__construct(
			'Oxy_Domain_Entity_EventSourcedInterface', 
			$collectionItems
		);		
	}    
}