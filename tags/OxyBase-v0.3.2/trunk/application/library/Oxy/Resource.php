<?php
/**
* Resource component
*
* Usage:
* Oxy_Resource::create('K:/Development/Test', 'directory');
* Oxy_Resource::create('K:/Development/Test/main.css', 'css');
* Oxy_Resource::create('K:/Development/Test/view.tpl', 'tpl');
* Oxy_Resource::create('K:/Development/Test/controller.php', 'phpcode', 'K:/Development/Workspace/OxyBase-GoogleCode/trunk/profiles/OxyBase/templates/controller/OxySimple.php');
* Oxy_Resource::create('K:/Development/Test/run.js', 'js');
*
* @category Oxy
* @package Oxy_Resource
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
class Oxy_Resource
{
    /**
     * Directory
     *
     * @var String
     */
    const RESOURCE_DIRECTORY = 'directory';

	/**
	 * Create new resource
	 *
	 * @param String $str_name - resource name (file name, dir name etc...)
	 * @param String $str_type - resource type css, phpcode, tpl, xml
	 * @param String $str_tpl - resource template, which will be applied as content
	 *
	 * @return boolean
	 */
	public static function create($str_name = null, $str_type = null, $str_tpl = null)
	{
	    if(is_null($str_type))
	    {
	        throw new Oxy_Resource_Exception('Resource type can not be null!');
	    }

	    if(is_null($str_name))
	    {
	        throw new Oxy_Resource_Exception('Resource name can not be null!');
	    }

	    $str_ucfirst_type = ucfirst($str_type);
	    if($str_type === self::RESOURCE_DIRECTORY)
	    {
	        $str_resource_class = 'Oxy_Resource_Directory';
	        require_once 'Oxy/Resource/Directory.php';
	    }
	    else
	    {
	        $str_resource_class = 'Oxy_Resource_File_'.$str_ucfirst_type;
	        require_once "Oxy/Resource/File/".$str_ucfirst_type.".php";
	    }

	    $obj_resource = new $str_resource_class($str_name, $str_tpl);
	    if(!($obj_resource instanceof Oxy_Resource_Abstract))
	    {
	        throw new Oxy_Resource_Exception('Resource must be an instance of Oxy_Resource_Abstract!');
	    }

	    return (boolean)$obj_resource->create();
	}
}