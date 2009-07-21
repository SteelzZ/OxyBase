<?php

/**
 * Frontcontroller handler resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_Frontcontroller extends Zend_Application_Resource_ResourceAbstract
{

	/**
	 * @var Oxy_Controller_Front
	 */
	protected $obj_front;

	/**
	 * Initialize Front Controller
	 *
	 * @return Oxy_Controller_Front
	 */
	public function init()
	{
		$obj_dispatcher = new Oxy_Controller_Dispatcher_Domain();
		$obj_request = new Oxy_Controller_Request_Http();

		$front = $this->getFrontController();
		$front->setRequest($obj_request);

		$obj_router = new Oxy_Controller_Router_Rewrite();
		$obj_router->addRoute('domain', new Oxy_Controller_Router_Route_Domain(array(),
																			   $obj_dispatcher,
																			   $obj_request));

		$front->setRouter($obj_router);
		$front->setDispatcher($obj_dispatcher);

		foreach ($this->getOptions() as $key => $value)
		{
			switch (strtolower($key))
			{
				case 'controllerdirectory':
					if (is_string($value))
					{
						$front->setControllerDirectory($value);
					}
					elseif (is_array($value))
					{
						foreach ($value as $str_domain => $arr_data)
						{
							$front->addControllerDirectory($arr_data['directory'],
														   $arr_data['module'],
														   $str_domain);
						}
					}
					break;
				case 'modulecontrollerdirectoryname':
					$front->setModuleControllerDirectoryName($value);
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
					foreach ((array) $value as $pluginClass)
					{
						$plugin = new $pluginClass();
						$front->registerPlugin($plugin);
					}
					break;
				case 'throwexceptions':
					$front->throwExceptions((bool) $value);
					break;
				case 'actionhelperpaths':
					if (is_array($value))
					{
						foreach ($value as $helperPrefix => $helperPath)
						{
							Zend_Controller_Action_HelperBroker::addPath($helperPath, $helperPrefix);
						}
					}
					break;
				default:
					$front->setParam($key, $value);
					break;
			}
		}

		if (null !== ($bootstrap = $this->getBootstrap()))
		{
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
		if (null === $this->obj_front)
		{
			$this->obj_front = Oxy_Controller_Front::getInstance();
		}
		return $this->obj_front;
	}
}
