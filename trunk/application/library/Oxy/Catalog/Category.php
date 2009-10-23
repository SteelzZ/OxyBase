<?php
/**
* Concrete catalog component - Category
*
* @category Oxy
* @package Oxy_Catalog
* @author Tomas Bartkus
**/
class Oxy_Catalog_Category extends Oxy_Catalog_Abstract
{
	/**
	 * Children array
	 *
	 * @var Array
	 */
	protected $arrChildren = array();

	/**
	 *
	 * @param Oxy_Catalog_Abstract $obj_component
	 *
	 * @return Boolean
	 * @see Oxy_Catalog_Abstract::add()
	 */
	public function add(Oxy_Catalog_Abstract $obj_component)
	{
 		$this->arrChildren[$obj_component->getId()] = $obj_component;
	}

	/**
	 *
	 * @param Boolean $bl_deep
	 * @return Array
	 * @see Oxy_Catalog_Abstract::getChildren()
	 */
	public function getChildren($bl_deep = false)
	{
		return $this->arrChildren;
	}

    /**
	 *
	 * @param String $str_id
	 * @return Array
	 * @see Oxy_Catalog_Abstract::getChild()
	 */
    public function getChild($str_id)
    {
    	if(isset($this->arrChildren[$str_id]))
    	{
    		return $this->arrChildren[$str_id];
    	}

    	return false;
    }

	/**
	 *
	 * @return Boolean
	 * @see Oxy_Catalog_Abstract::isLeaf()
	 */
	public function isLeaf()
	{
		return false;
	}

	/**
	 *
	 * @param Oxy_Catalog_Abstract $obj_component
	 * @return Boolean
	 * @see Oxy_Catalog_Abstract::remove()
	 */
	public function remove(Oxy_Catalog_Abstract $obj_component)
	{
		unset($this->arrChildren[$obj_component->getId()]);
	}
}
?>