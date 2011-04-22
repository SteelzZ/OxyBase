<?php

/**
 * Oxy view render
 *
 * @category Oxy
 * @package Controller
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Controller_Action_Helper_ViewRenderer extends Zend_Controller_Action_Helper_ViewRenderer
{

	/**
	 * Get current domain name
	 *
	 * @return string
	 */
	public function getDomain()
	{
		$obj_request = $this->getRequest();
		$str_domain = $obj_request->getDomainName();
		if (null === $str_domain)
		{
			$str_domain = $this->getFrontController()->getDispatcher()->getDefaultDomain();
		}
		return $str_domain;
	}

	/**
	 * Get module directory
	 *
	 * @throws Zend_Controller_Action_Exception
	 * @return string
	 */
	public function getModuleDirectory()
	{
		$str_domain = $this->getDomain();
		$module = $this->getModule();
		$moduleDir = $this->getFrontController()->getControllerDirectory($module, $str_domain);
		if ((null === $moduleDir) || is_array($moduleDir))
		{
			/**
			 * @see Zend_Controller_Action_Exception
			 */
			require_once 'Zend/Controller/Action/Exception.php';
			throw new Zend_Controller_Action_Exception('ViewRenderer cannot locate module directory');
		}
		$this->_moduleDir = dirname($moduleDir);
		return $this->_moduleDir;
	}

	/**
	 * Generate a class prefix for helper and filter classes
	 *
	 * @return string
	 */
	protected function _generateDefaultPrefix()
	{
		$default = 'Zend_View';
		if (null === $this->_actionController)
		{
			return $default;
		}
		$class = get_class($this->_actionController);
		if (! strstr($class, '_'))
		{
			return $default;
		}
		$module = $this->getModule();
		if ('default' == $module)
		{
			return $default;
		}

		$arr_segments = explode('_', $class);

		$prefix = array_shift($arr_segments) . '_' . array_shift($arr_segments) . '_View';

		return $prefix;
	}
}
?>