<?php
/**
 * Entity collection
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Entity
 * @author Tomas Bartkus
 **/
class Oxy_Domain_Entity_Collection extends Oxy_Collection
{
	/**
	 * @param string $valueType - not used, left because of STRICT
	 * @param array $collectionItems
	 */
	public function __construct($valueType = '', Array $collectionItems = array())
	{
		parent::__construct(
			'Oxy_Domain_AbstractEntity', 
			$collectionItems
		);		
	}    
}