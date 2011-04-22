<?php
/**
* Concrete primitive catalog component - Element
*
* @category Oxy
* @package Oxy_Catalog
* @author Tomas Bartkus
**/
class Oxy_Catalog_Element extends Oxy_Catalog_Abstract
{

	/**
	 *
	 * @param Oxy_Catalog_Abstract $obj_component
	 * @return Boolean
	 * @see Oxy_Catalog_Abstract::add()
	 */
	public function add(Oxy_Catalog_Abstract $obj_component)
	{
		return false;
	}

	/**
	 *
	 * @param Boolean $bl_deep
	 * @return ArrayIterator
	 * @see Oxy_Catalog_Abstract::getChildren()
	 */
	public function getChildren($bl_deep = false)
	{
		return false;
	}

	/**
	 *
	 * @return Boolean
	 * @see Oxy_Catalog_Abstract::isLeaf()
	 */
	public function isLeaf()
	{
		return true;
	}

	/**
	 *
	 * @param Oxy_Catalog_Abstract $obj_component
	 * @return Boolean
	 * @see Oxy_Catalog_Abstract::remove()
	 */
	public function remove(Oxy_Catalog_Abstract $obj_component)
	{
		return false;
	}

    /**
	 *
	 * @param String $str_id
	 * @return Boolean
	 * @see Oxy_Catalog_Abstract::getChild()
	 */
    public function getChild($str_id)
    {
        return false;
    }
}
?>