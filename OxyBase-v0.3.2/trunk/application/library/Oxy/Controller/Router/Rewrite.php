<?php
/**
 * Oxy router
 *
 * @category Oxy
 * @package Oxy_Controller
 * @subpackage Router
 * @author Tomas Bartkus
 **/
class Oxy_Controller_Router_Rewrite extends Zend_Controller_Router_Rewrite
{
	/**
	 * Override
	 *
	 * @param Oxy_Controller_Request_Http $request
	 * @param Array $params
	 */
	protected function _setRequestParams($request, $arr_params)
	{
		foreach ($arr_params as $str_param => $str_value)
		{
			$request->setParam($str_param, $str_value);
			if ($str_param === $request->getDomainKey())
			{
				$request->setDomainName($str_value);
			}
			if ($str_param === $request->getModuleKey())
			{
				$request->setModuleName($str_value);
			}
			if ($str_param === $request->getControllerKey())
			{
				$request->setControllerName($str_value);
			}
			if ($str_param === $request->getActionKey())
			{
				$request->setActionName($str_value);
			}
		}
	}
}
?>