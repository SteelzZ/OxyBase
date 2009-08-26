<?php
/**
 * Conf resource
 *
 * @category Oxy
 * @package Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_Conf extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * Const
	 */
	const DEFAULT_REGISTRY_KEY = 'obj_config';

    /**
     * @var Zend_Config
     */
    protected $obj_config;

    /**
     * Initialize config
     *
     * @return Zend_Config
     */
    public function init()
    {
    	$bl_use_multi_domains = false;
    	foreach ($this->getOptions() as $key => $value)
    	{
            switch (strtolower($key))
            {
                case 'usemultidomains':
                	$bl_use_multi_domains = (boolean) $value;
                	break;
            }
        }
        return $this->getConfig($bl_use_multi_domains);
    }

    /**
     * Retrieve config
     * Same application can have different domains app.lt app.com etc.
     * load config depending on domain config_lt.xml when user hits app.lt
     * if no domain found load just config.xml
     *
     * @param Boolean $bl_use_multi_domains
     * 
     * @return Zend_Config
     */
    public function getConfig($bl_use_multi_domains = false)
    {
    	$_SERVER['HTTP_HOST'] = isset($_SERVER['HTTP_HOST']) ?
    										$_SERVER['HTTP_HOST'] : '';
     	$arr_data = explode('.', $_SERVER['HTTP_HOST']);

		if($bl_use_multi_domains && isset($arr_data[2]) && 
		   Zend_Validate::is($arr_data[2], 'Int') !== false)
		{
			$this->obj_config = new Zend_Config_Xml(APPLICATION_PATH . 'config/config_'.$arr_data[2].'.xml',
													APPLICATION_ENV);
		}
		else
		{
			$this->obj_config = new Zend_Config_Xml(APPLICATION_PATH . 'config/config.xml',
													APPLICATION_ENV);
		}

		Zend_Registry::set(self::DEFAULT_REGISTRY_KEY, $this->obj_config);

        return $this->obj_config;
    }
}
