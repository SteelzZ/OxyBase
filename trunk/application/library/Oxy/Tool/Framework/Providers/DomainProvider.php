<?php
require_once 'Zend/Tool/Framework/Provider/Interface.php';
/**
* Oxy Application domain tools provider
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Framework
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Framework_Provider_DomainProvider implements Zend_Tool_Framework_Provider_Interface
{
	/**
	 * Create new domain
	 *
	 * @param $str_domain
	 * @param $bl_full
	 */
	public function create($str_path_to_domain = null, $bl_full = true)
	{
		if (is_null($str_path_to_domain))
		{
            $str_path_to_domain = getcwd();
        }
        else
        {
            $str_path_to_domain = trim($str_path_to_domain);
            if (!file_exists($str_path_to_domain))
            {
                $bl_created = mkdir($str_path_to_domain, 0777);
                if (!$bl_created)
                {
                    require_once 'Zend/Tool/Framework/Client/Exception.php';
                    throw new Zend_Tool_Framework_Client_Exception('Could not create requested domain directory \'' . $str_path_to_domain . '\'');
                }
                else
                {
                	$str_path_to_domain = str_replace('\\', '/', realpath($str_path_to_domain));

                	$bl_created = mkdir($str_path_to_domain . '/config', 0777);
                	$bl_created = mkdir($str_path_to_domain . '/library', 0777);
                	$bl_created = mkdir($str_path_to_domain . '/modules', 0777);
                	$bl_created = mkdir($str_path_to_domain . '/resources', 0777);
                }
            }
        }
	}

	/**
	 * Return all domains
	 *
	 * @return String
	 */
	public function show()
	{

	}

	/**
	 * Delete domain
	 *
	 * @param $str_domain
	 */
	public function delete($str_path_to_domain = null)
	{
		if (is_null($str_path_to_domain))
		{
            require_once 'Zend/Tool/Framework/Client/Exception.php';
            throw new Zend_Tool_Framework_Client_Exception('You must provide domain name! Invalid domain - \'' . $str_path_to_domain . '\'');
        }
        else
        {
        	$str_path_to_domain = trim($str_path_to_domain);
        	if (file_exists($str_path_to_domain))
            {
                $bl_deleted = unlink($str_path_to_domain);
                if (!$bl_deleted)
                {
                    require_once 'Zend/Tool/Framework/Client/Exception.php';
                    throw new Zend_Tool_Framework_Client_Exception('Could not delete requested domain directory \'' . $str_path_to_domain . '\'');
                }
            }
        }

	}
}
?>