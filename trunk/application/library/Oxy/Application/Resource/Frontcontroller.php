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
	protected $obj_front;

	/**
	 * Initialize Oxy Front Controller
	 *
	 * @return Oxy_Controller_Front
	 */
	public function init()
	{
		// @TODO Factory is needed here
		$objDispatcher = new Oxy_Controller_Dispatcher_Domain();
		$objRequest = new Oxy_Controller_Request_Http();

		$objFront = $this->getFrontController();

		$objRouter = new Oxy_Controller_Router_Rewrite();
		$objRouter->addRoute('domain', new Oxy_Controller_Router_Route_Domain(array(),
																			  $objDispatcher,
																			  $objRequest));
		$objFront->setRouter($objRouter);
		$objFront->setDispatcher($objDispatcher);



		foreach ($this->getOptions() as $key => $value)
		{
			switch (strtolower($key))
			{
				case 'controllerdirectory':
					if (is_string($value))
					{
						$objFront->setControllerDirectory($value);
					}
					elseif (is_array($value))
					{
						foreach ($value as $str_domain => $arr_data)
						{
							$objFront->addControllerDirectory($arr_data['directory'],
														   $arr_data['module'],
														   $str_domain);
						}
					}
					break;
				case 'modulecontrollerdirectoryname':
					$objFront->setModuleControllerDirectoryName($value);
					break;
				case 'domaindirectory':
					$objFront->addDomainDirectory($value);
					break;
				case 'defaultcontrollername':
					$objFront->setDefaultControllerName($value);
					break;
				case 'defaultaction':
					$objFront->setDefaultAction($value);
					break;
				case 'defaultmodule':
					$objFront->setDefaultModule($value);
					break;
				case 'defaultdomain':
					$objFront->setDefaultDomain($value);
					break;
				case 'baseurl':
					$objFront->setBaseUrl($value);
					break;
				case 'params':
					$objFront->setParams($value);
					break;
				case 'plugins':
					foreach ((array) $value as $pluginClass)
					{
						$plugin = new $pluginClass();
						$objFront->registerPlugin($plugin);
					}
					break;
				case 'throwexceptions':
					$objFront->throwExceptions((bool) $value);
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
					$objFront->setParam($key, $value);
					break;
			}
		}

		$objRequest = $objRouter->route($objRequest);

		$objFront->setRequest($objRequest);

		if (null !== ($bootstrap = $this->getBootstrap()))
		{
			$this->getBootstrap()->frontController = $objFront;
		}

		return $objFront;
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
