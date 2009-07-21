<?php
/**
* Concrete primitive catalog component - Element
*
* @category Oxy
* @package Oxy_Catalog
* @author Tomas Bartkus
* @version 1.0
**/
class Oxy_Catalog_Element extends Oxy_Catalog_Abstract
{

	/**
	 *
	 * @param Oxy_Catalog_Component $obj_component
	 * @return Boolean
	 * @see Oxy_Catalog_Component::add()
	 */
	public function add(Oxy_Catalog_Component $obj_component)
	{
		return false;
		//TODO - Insert your code here
	}

	/**
	 *
	 * @param Boolean $bl_deep
	 * @return ArrayIterator
	 * @see Oxy_Catalog_Component::getChildren()
	 */
	public function getChildren($bl_deep = false)
	{
		return false;
	}

	/**
	 *
	 * @return Boolean
	 * @see Oxy_Catalog_Component::isLeaf()
	 */
	public function isLeaf()
	{
		return true;
	}

	/**
	 *
	 * @param Oxy_Catalog_Component $obj_component
	 * @return Boolean
	 * @see Oxy_Catalog_Component::remove()
	 */
	public function remove(Oxy_Catalog_Component $obj_component)
	{
		return false;
	}

    /**
	 *
	 * @param String $str_id
	 * @return Boolean
	 * @see Oxy_Catalog_Component::getChild()
	 */
    public function getChild($str_id)
    {
        return false;
    }
}
?>