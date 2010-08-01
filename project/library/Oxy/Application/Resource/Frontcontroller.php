<?php

/**
 * Front handler resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_Frontcontroller extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * Front controller
	 *
	 * @var Oxy_Controller_Front
	 */
	protected $front;

	/**
	 * Initialize Oxy Front Controller
	 *
	 * @return Oxy_Controller_Front
	 */
	public function init()
	{
		$front = $this->getFrontController();
		foreach ($this->getOptions() as $key => $value){
			switch (strtolower($key)){
				case 'controllerdirectory':
					if (is_string($value)){
						$front->setControllerDirectory($value);
					} elseif (is_array($value)){
						foreach ($value as $domain => $arr_data){
							$front->addControllerDirectory($arr_data['directory'],
														   $arr_data['module'],
														   $domain);
						}
					}
					break;
				case 'modulecontrollerdirectoryname':
					$front->setModuleControllerDirectoryName($value);
					break;
				case 'moduledirectory':
                    $front->addModuleDirectory($value);
                    break;
				case 'domaindirectory':
					$front->addDomainDirectory($value);
					break;
				case 'defaultcontrollername':
					$front->setDefaultControllerName($value);
					break;
				case 'defaultaction':
					$front->setDefaultAction($value);
					break;
				case 'defaultmodule':
					$front->setDefaultModule($value);
					break;
				case 'defaultdomain':
					$front->setDefaultDomain($value);
					break;
				case 'baseurl':
					$front->setBaseUrl($value);
					break;
				case 'params':
					$front->setParams($value);
					break;
				case 'plugins':
					foreach ((array) $value as $pluginClass => $stackIndex){
						// Register only those front plugins that has not been registered before
						if (!$front->hasPlugin($pluginClass)){
	                        $stackIndex = (ctype_digit($stackIndex) ? (int)$stackIndex : null);
	                        $plugin = new $pluginClass();
                            $front->registerPlugin($plugin, $stackIndex);
						}
					}
					break;
				case 'throwexceptions':
					$front->throwExceptions((bool)$value);
					break;
				case 'actionhelperpaths':
					if (is_array($value)){
						foreach ($value as $helperPrefix => $helperPath){
							Zend_Controller_Action_HelperBroker::addPath($helperPath, $helperPrefix);
						}
					}
					break;
				default:
					$front->setParam($key, $value);
					break;
			}
		}
		
		if (null !== ($bootstrap = $this->getBootstrap())){
			$this->getBootstrap()->frontController = $front;
		}
		
		return $front;
	}

	/**
	 * Retrieve front controller instance
	 *
	 * @return Oxy_Controller_Front
	 */
	public function getFrontController()
	{
		if (null === $this->front){
			$this->front = Oxy_Controller_Front::getInstance();
		}
		
		return $this->front;
	}
}
