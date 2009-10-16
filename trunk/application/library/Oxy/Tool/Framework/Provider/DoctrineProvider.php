<?php
require_once 'Zend/Tool/Framework/Provider/Abstract.php';
require_once 'Doctrine.php';

/**
* Oxy doctrine provider
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Framework
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Framework_Provider_DoctrineProvider extends Zend_Tool_Framework_Provider_Abstract
{
	/**
	 * Generate ORM classes from  databse
	 *
	 * Tips:
	 * mysql://cw_user:cwuser@192.168.10.69/dirp
	 * ../../../application/domains/bot/resources/db/tables/schema.yml
	 * '../../../application/domains/bot/resources/db/tables'
	 *
	 * @param string $strDsn
	 * @param string $str_path_to_yaml
	 * @param string $str_path_to_models
	 * @param boolean $blGenerateBaseClasses
	 * @param boolean $blGenerateTableClasses
	 */
	public function generateModels($strDsn,
	                               $str_path_to_yaml,
	                               $str_path_to_models,
	                               $blGenerateBaseClasses = true,
	                               $blGenerateBaseClasses = true)
	{
         spl_autoload_register(array('Doctrine', 'autoload'));
         $objManager = Doctrine_Manager::getInstance();

         /**
          * Make a connection
          */
         $objConn = $objManager->connection($strDsn);

         $arrOptions = array(
                "generateBaseClasses" => $blGenerateBaseClasses,
                "generateTableClasses" => $blGenerateBaseClasses
         );

         Doctrine::generateYamlFromDb($str_path_to_yaml, array(), $arrOptions);
         Doctrine::generateModelsFromYaml($str_path_to_yaml, $str_path_to_models, $arrOptions);
	}
}
?>