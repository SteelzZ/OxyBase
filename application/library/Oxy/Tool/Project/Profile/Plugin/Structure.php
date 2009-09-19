<?php
require_once 'Oxy/Tool/Project/Profile/Plugin/Abstract.php';
require_once 'Oxy/Tool/Project/Profile/Plugin/Exception.php';
require_once 'Zend/Loader.php';

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
	 * Generate XML structure by given
	 * folder structure
	 *
	 * @param Array $arr_params
	 *
	 * @return void
	 */
	public function generate(Array $arr_params = array())
	{
	    if(isset($arr_params[2]) && !empty($arr_params[2]))
		{
			//$this->initElement($arr_params[2]);
		}
		else
		{
		   //$this->initElement('profile');
		}

	    $this->initBasepath();
	    $obj_dom = new DOMDocument('1.0','utf-8');
	    $obj_structure_element = $obj_dom->createElement('structure', "\n");
	    $this->analyzePath($this->str_base_path,
	                       array('Oxy', '.svn', 'Doctrine', 'Zend', 'Compiler', 'Cmp', 'public'),
	                       array(),
	                       $obj_dom,
	                       $obj_structure_element);
        $obj_dom->appendChild($obj_structure_element);
        print "v0.1 Directory structure: \n\n";
	    print_r($obj_dom->saveXML());
	}

	/**
	 * Delete given element
	 *
	 * @param Array $arr_params
	 *
	 * @return void
	 */
	public function delete(Array $arr_params = array())
	{
	    if(!isset($arr_params[2]) || empty($arr_params[2]))
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception('Element name can not be null!');
		}
		$this->initElement($arr_params[2]);
		$this->initBasepath();
		unset($arr_params[2]);

		//$this->deleteElement($this->obj_element, $arr_params);
	}

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
	 * @important All abstract elements must have unique node name!!
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

			// Check if elements should be created automatically
			// from passed resources
			$str_init_node_auto = $obj_init_node->getAttribute('auto');

			// If it is auto dir build path from one level higher node,
			// parent node
			if(is_string($str_init_node_auto) && !empty($str_init_node_auto))
			{
				$this->findAbstracts($obj_init_node->parentNode, $arr_concrete_names);
			}
			else
			{
				$this->findAbstracts($obj_init_node, $arr_concrete_names);
			}

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

			// Create auto resources or normal directory
			if(is_string($str_init_node_auto) && !empty($str_init_node_auto))
			{
				$this->updateAuto($obj_init_node, $str_element_path);
			}
			else
			{
				$bl_created = mkdir($str_element_path);
				if(!$bl_created)
				{
					throw new Oxy_Tool_Project_Profile_Plugin_Exception('Could not create dir!');
				}
			}
		}

		// Get child nodes
		$obj_list = $obj_init_node->childNodes;

		foreach ($obj_list as $obj_node)
		{
			if($obj_node instanceof DOMElement)
			{
				// Handle files
				if($obj_node->nodeName == 'resource')
				{
					$str_resources = $obj_init_node->getAttribute('accepted_resources');
					$arr_accepted_resources = explode(';', $str_resources);
					$str_current_res_type = $obj_node->getAttribute('type');

					// Check if element accepts such resources
					if(in_array($str_current_res_type, $arr_accepted_resources))
					{
						// Create resource
						$this->touch($obj_node, $str_element_path);
					}

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
	 * Create new file
	 *
	 * @param DOMElement $obj_node
	 * @param String $str_current_path
	 *
	 * @return void
	 */
	private function touch(DOMElement $obj_node, $str_path = null)
	{
		if(is_null($str_path))
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception('Path to resource directory can not be null!');
		}

		$str_type = $obj_node->getAttribute('type');
		$str_tpl = $obj_node->getAttribute('tpl');
	    if(is_string($str_tpl) && !empty($str_tpl))
		{
			$str_tpl = $this->str_base_path . "profiles/OxyBase/templates/$str_type/$str_tpl.$str_type";
		}
		else
		{
		    $str_tpl = null;
		}

		// Apply filters
		$str_name = $this->filterFilename($obj_node->nodeValue);


		$str_path = $str_path . '/' . $str_name;

		require_once "Oxy/Resource.php";
		Oxy_Resource::create($str_path, $str_type, $str_tpl);
		//touch($str_path);
	}

	/**
	 * Apply template to file
	 *
	 * @param $str_path_to_file
	 * @param $str_template
	 * @return unknown_type
	 */
	private function applyTemplate($str_path_to_file = null, $str_template = '')
	{
		//$str_tpl_path = $this->str_base_path . 'profiles/';
		//file_get_contents()
	}

	/**
	 * Filter file name
	 *
	 * @param String $str_value
	 * @return String
	 */
	private function filterFilename($str_value)
	{
		return $str_value;
	}

	/**
	 * Update auto dirs
	 *
	 * @param DOMElement $obj_node
	 * @param String $str_current_path
	 *
	 * @return void
	 */
	private function updateAuto(DOMElement $obj_node, $str_current_path = null)
	{
		if(is_null($str_current_path))
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception('Path to resource directory can not be null!');
		}

		// Get path to resources dir
		// we will take this "auto" path resources (all child files and dirs)
		// and will refelct it in $str_current_path
		$str_path = $obj_node->getAttribute('auto');
		$arr_path_parts = explode('/', $str_path);

		$arr_current_path_parts = explode('/', $str_current_path);

		// Remove last one because "auto" dir is abstract
		// so we need to create dirs in one level higher dir
		unset($arr_current_path_parts[sizeof($arr_current_path_parts)-1]);
		$str_current_path = implode('/', $arr_current_path_parts);

		// Navigate to directory where to read files from
		foreach ($arr_path_parts as $str_part)
		{
			if($str_part == '..')
			{
				unset($arr_current_path_parts[sizeof($arr_current_path_parts)-1]);
			}
			else if(is_string($str_part) && $str_path != '..')
			{
				array_push($arr_current_path_parts, $str_part);
			}
		}

		$str_read_path = implode('/', $arr_current_path_parts);

		try
		{
			$obj_dir = new DirectoryIterator($str_read_path);
		}
		catch (Exception $e)
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception("Directory $str_read_path is not readable");
		}

		foreach ($obj_dir as $obj_file)
		{
			if ($obj_file->isDot())
			{
				continue;
			}

			if ($obj_file->isDir() || $obj_file->isFile())
			{
				$str_resource = $obj_file->getFilename();

				$str_resource = $this->filterDirName($str_resource);


				$str_create_path = $str_current_path .'/'.$str_resource;

				if(!file_exists($str_create_path))
				{
					$bl_created = mkdir($str_create_path);
					if(!$bl_created)
					{
						throw new Oxy_Tool_Project_Profile_Plugin_Exception("Could not create dir - {$str_current_path}!");
					}
				}
			}
		}
	}

	/**
	 * Apply filters for directory name
	 *
	 * @param String $str_value
	 * @return String
	 */
	private function filterDirName($str_value = '')
	{
		require_once 'Zend/Filter/StringToLower.php';
		$obj_filter = new Zend_Filter_StringToLower();
		$str_value = $obj_filter->filter($str_value);

		require_once 'Zend/Filter/PregReplace.php';
		$obj_filter = new Zend_Filter_PregReplace('/\.([a-zA-z]*)$/', '');
		$str_value = $obj_filter->filter($str_value);

		require_once 'Zend/Filter/PregReplace.php';
		$obj_filter = new Zend_Filter_PregReplace('/controller/', '');
		$str_value = $obj_filter->filter($str_value);

		return $str_value;
	}

	/**
	 *
	 * @param $str_path
	 * @return unknown_type
	 */
	private function analyzePath($str_path = '',
	                             Array $arr_black_list = array('.svn'),
	                             Array $arr_white_list = array(),
	                             DOMDocument &$obj_dom = null,
	                             DOMElement &$obj_element = null)
	{
	    $str_base_path = str_replace('\\', '/', $str_path);
	    $arr_bath_path = explode('/', $str_base_path);

	    // Init dir
	    try
		{
			$obj_dir = new RecursiveDirectoryIterator($str_path);
		}
		catch (Exception $e)
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception("Directory $str_path is not readable");
		}

    	foreach($obj_dir as $obj_file)
    	{
  			if ($obj_file->isDir() /**|| $obj_file->isFile()**/)
			{
			    if(in_array($obj_file->getFilename(), $arr_black_list))
			    {
			        continue;
			    }

			    if(!empty($arr_white_list))
			    {
			        if(!in_array($obj_file->getFilename(), $arr_white_list))
			        {
			            continue;
			        }
			    }

			    $str_required_path = $obj_file->getPathname();
			    $str_required_path = str_replace('\\', '/', $str_required_path);
				$arr_nodes = explode('/', $str_required_path);
				$arr_required = (array)(array_diff($arr_nodes, $arr_bath_path));

				$str_node = array_shift($arr_required);

				if ($obj_file->isDir())
				{
				    if(empty($str_node))
				    {
				        continue;
				    }
				    $str_value = "\n";
				    $obj_current_element = $obj_dom->createElement($str_node, $str_value);
				    $this->analyzePath($str_required_path,
				                       $arr_black_list,
				                       $arr_white_list,
				                       $obj_dom,
				                       $obj_current_element);
				}

				$obj_element->appendChild($obj_current_element);
            }
        }
	}

}
?>