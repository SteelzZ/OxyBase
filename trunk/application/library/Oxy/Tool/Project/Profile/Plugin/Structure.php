<?php
require_once 'Oxy/Tool/Project/Profile/Plugin/Abstract.php';
require_once 'Oxy/Tool/Project/Profile/Plugin/Exception.php';

/**
* Structure plugin
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Project_Profile_Plugin_Structure extends Oxy_Tool_Project_Profile_Plugin_Abstract
{
	/**
	 * Structure element
	 *
	 * @var DOMNode
	 */
	private $obj_element;

	/**
	 * Base path
	 *
	 * @var String
	 */
	private $str_base_path;

	/**
	 * Create structure element
	 *
	 * @param Array $arr_params
	 *
	 * @return void
	 */
	public function create(Array $arr_params = array())
	{
		if(!isset($arr_params[2]) || empty($arr_params[2]))
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception('Element name can not be null!');
		}
		$this->initElement($arr_params[2]);
		$this->initBasepath();
		unset($arr_params[2]);

		$this->createElements($this->obj_element, $arr_params);
	}

	/**
	 * Initialize base path
	 *
	 * @return void
	 */
	private function initBasepath()
	{
		$obj_list = $this->obj_profile->getElementsByTagName('structure');
		if(!($obj_list instanceof DOMNodeList))
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception("Root plugin node 'structure'does not exists in xml!");
		}
		$obj_element = $obj_list->item(0);
		$this->str_base_path = (string)$obj_element->getAttribute('base_path');
		if(empty($this->str_base_path))
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception("You must set @base_path attribute in <structure> node!");
		}
	}

	/**
	 * Initialize element to create
	 *
	 * @param $str_name
	 *
	 * @return DOMNode
	 */
	private function initElement($str_name = 'structure')
	{
		$obj_list = $this->obj_profile->getElementsByTagName($str_name);
		if(!($obj_list instanceof DOMNodeList))
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception("Element '{$str_name}' that you want to create does not exists in xml!");
		}
		$this->obj_element = $obj_list->item(0);
	}

	/**
	 * Create elements
	 *
	 * @param DOMNode $obj_node
	 *
	 * @return DOMNode
	 */
	private function createElements(DOMElement $obj_init_node,
									Array $arr_names = array(),
									$str_element_path = '')
	{
		$bl_init_node_is_abstract = (boolean)$obj_init_node->getAttribute('abstract');

		// Initialize base node
		if($bl_init_node_is_abstract)
		{
			$int_param_index = 3;
			$arr_concrete_names = array();
			$this->findAbstracts($obj_init_node, $arr_concrete_names);

			$arr_keys = array_keys($arr_concrete_names);
			foreach ($arr_keys as $str_key)
			{
				// If we still have params
				if(count($arr_names) > 0)
				{
					$arr_concrete_names[$str_key] = trim(array_shift($arr_names));
					unset($arr_names[$int_param_index]);
				}
			}

			// Format path
			$str_element_path = $this->formatPath($obj_init_node, $arr_concrete_names);
			$str_element_path = $this->str_base_path.$str_element_path;
			$bl_created = mkdir($str_element_path);
			if(!$bl_created)
			{
				throw new Oxy_Tool_Project_Profile_Plugin_Exception('Could not create dir!');
			}
		}

		// Get child nodes
		$obj_list = $obj_init_node->childNodes;

		foreach ($obj_list as $obj_node)
		{
			if($obj_node instanceof DOMElement)
			{
				if($obj_node->nodeName == 'resource')
				{
					continue;
				}

				// Directory is abstract ?
				$bl_abstract = $obj_node->getAttribute('abstract');

				// If abstract dir move to next one
				if($bl_abstract)
				{
					continue;
				}

				$str_create_path = $str_element_path.'/'.(string)$obj_node->nodeName;
				$bl_created = mkdir($str_create_path);
				if(!$bl_created)
				{
					throw new Oxy_Tool_Project_Profile_Plugin_Exception('Could not create dir!');
				}

				if($obj_node->hasChildNodes())
				{
					$this->createElements($obj_node, $arr_names, $str_create_path);
				}
			}
		}
	}

	/**
	 * Go backwards from given node and check how many
	 * abstract nodes we have. If default values are passed
	 * set them
	 *
	 * @param DOMElement $obj_node
	 * @param Array $arr_names
	 * @return void
	 */
	private function findAbstracts(DOMElement $obj_node, Array &$arr_names = array())
	{
		$bl_abstract = (boolean)$obj_node->getAttribute('abstract');
		if($bl_abstract)
		{
			$str_default_name = (string)$obj_node->getAttribute('default');
			if(is_string($str_default_name) && !empty($str_default_name))
			{
				$arr_names[$obj_node->nodeName] = $str_default_name;
			}
		}

		if($obj_node->parentNode instanceof DOMElement)
		{
			return $this->findAbstracts($obj_node->parentNode, $arr_names);
		}
	}


	/**
	 * Find node parents and build path
	 * fill abstract nodes with concrete ones
	 *
	 * @return String
	 */
	private function formatPath(DOMElement $obj_node, Array $arr_names = array())
	{
		// Get path to node
		$str_path = $obj_node->getNodePath();

		$arr_abstract_nodes = array_keys($arr_names);

		// Remove structure node, we do not need it
		$arr_parts = explode('/', $str_path);
		unset($arr_parts[0]);
		unset($arr_parts[1]);

		foreach($arr_parts as $str_key => &$str_name)
		{
			if(array_key_exists($str_name, $arr_names))
			{
				$str_name = $arr_names[$str_name];
			}
		}

		$str_path = implode('/', $arr_parts);

		return $str_path;
	}

	/**
	 *
	 * @return unknown_type
	 */
	private function createFile()
	{

	}
}
?>