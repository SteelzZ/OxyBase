<?php
require_once 'Zend/Tool/Framework/Provider/Interface.php';
require_once 'Oxy/Compiler/Zend.php';

/**
* Oxy compiler provider
*
* @category Oxy
* @package Oxy_Framework
* @subpackage Provider
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Compiler_Provider_CompilerProvider implements Zend_Tool_Framework_Provider_Interface
{
	/**
	 *
	 * @var string $str_profile
	 * @var string $str_args
	 */
	public function execute($str_compiler_type, $str_path_to_compile, $str_compiled_file_name)
	{
	    // @TODO:Factory compiler
	    /**
        $obj_compiler = new Oxy_Compiler_Zend($str_path_to_compile);
        $obj_compiler->setCompiledFilename($str_compiled_file_name);
        $obj_compiler->registerFilters(array(
            new Oxy_Compiler_Text_Filter_Strip_Includes()
            ,new Oxy_Compiler_Text_Filter_Strip_Starttag()
            ,new Oxy_Compiler_Text_Filter_Strip_Endtag()
        ));
        /**/

	    echo "compile $str_path_to_compile as $str_compiled_file_name with $str_compiler_type compiler";
	}
}
?>