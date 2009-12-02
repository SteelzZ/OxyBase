<?php
/**
* Component base class
*
* @category Oxy
* @package Oxy_Catalog
* @author Tomas Bartkus
**/
abstract class Oxy_Catalog_Abstract
{
	/**
	 * Unique component ID
	 *
	 * @var String
	 */
	protected $_intId;

	/**
	 * Component title
	 *
	 * @var String
	 */
	protected $_strWord;

	/**
	 * Component data
	 *
	 * @var Array
	 */
	protected $_arrMetadata;

	/**
	 *  Initialize
	 */
	function __construct($str_id = null, $str_title = null, $arr_data = array())
	{
		$this->setId($str_id);
		$this->setTitle($str_title);
		$this->setData($arr_data);
	}

	/**
	 * JSON impl
	 *
	 * @return Zend_Json
	 */
	public function toJson()
	{
		$arr_data = array();
		$arr_data['title'] = $this->getTitle();
		$arr_data['id'] = $this->getId();
		$arr_data['data'] = $this->getData();
		return $arr_data;
	}

	/**
	 * @param Mix $mix_index
	 * @return Array
	 */
	public function getData($mix_index = null)
	{
		if(!is_null($mix_index))
		{
			if(array_key_exists($mix_index, $this->arr_data))
			{
				return $this->arr_data[$mix_index];
			}
			else
			{
				return false;
			}
		}
		else if (is_null($mix_index))
		{
			return $this->arr_data;
		}
	}

	/**
	 * @return String
	 */
	public function getId()
	{
		return $this->str_id;
	}

	/**
	 * @return String
	 */
	public function getTitle()
	{
		return $this->str_title;
	}

	/**
	 * @param Array $arr_data
	 */
	public function setData($arr_data)
	{
		$this->arr_data = $arr_data;
	}

	/**
	 * @param String $str_id
	 */
	public function setId($str_id)
	{
		$this->str_id = $str_id;
	}

	/**
	 * @param String $str_title
	 */
	public function setTitle($str_title)
	{
		$this->str_title = $str_title;
	}

	/**
	 * Add new component
	 *
	 * @param Oxy_Catalog_Abstract $obj_component
	 * @return Boolean
	 */
	public abstract function add(Oxy_Catalog_Abstract $obj_component);

	/**
	 * Remove component
	 *
	 * @param Oxy_Catalog_Abstract $obj_component
	 * @return Boolean
	 */
	public abstract function remove(Oxy_Catalog_Abstract $obj_component);

	/**
	 * Return true if component is primitive element
	 * false if it is composition
	 *
	 * @return Boolean
	 */
	public abstract function isLeaf();

	/**
	 * Get component children
	 *
	 * @param Boolean $bl_deep
	 *
	 * @return Array
	 */
	public abstract function getChildren($bl_deep = false);

    /**
	 * Get component child by id
	 *
	 * @param String $str_id
	 *
	 * @return Oxy_Catalog_Abstract
	 */
    public abstract function getChild($str_id);


}
?>