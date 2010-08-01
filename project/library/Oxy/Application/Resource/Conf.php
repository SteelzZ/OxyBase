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
    protected $objConfig;

    /**
     * Initialize config
     *
     * @return Zend_Config
     */
    public function init()
    {
    	$blUseMultiDomains = false;
    	foreach ($this->getOptions() as $key => $mixValue){
            switch (strtolower($key)){
                case 'usemultidomains':
                	$blUseMultiDomains = (boolean) $mixValue;
                	break;
            }
        }
        return $this->getConfig($blUseMultiDomains);
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
    public function getConfig($blUseMultiDomains = false)
    {
    	$_SERVER['HTTP_HOST'] = isset($_SERVER['HTTP_HOST']) ?
    										$_SERVER['HTTP_HOST'] : '';
     	$arrData = explode('.', $_SERVER['HTTP_HOST']);

		if($blUseMultiDomains && isset($arrData[2]) &&
		   Zend_Validate::is($arrData[2], 'Int') !== false){
			$this->objConfig = new Zend_Config_Xml(APPLICATION_PATH . '/config/config_'.$arrData[2].'.xml',
													APPLICATION_ENV);
		} else {
			$this->objConfig = new Zend_Config_Xml(APPLICATION_PATH . '/config/config.xml',
													APPLICATION_ENV);
		}

		Zend_Registry::set(self::DEFAULT_REGISTRY_KEY, $this->objConfig);

        return $this->objConfig;
    }
}
