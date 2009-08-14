<?php
/**
 * Route to route with domain
 *
 * @category Oxy
 * @package Oxy_Controller
 * @subpackage Router
 * @author Tomas Bartkus
 **/
class Oxy_Controller_Router_Route_Domain extends Zend_Controller_Router_Route_Module
{

	/**
	 * Domain key
	 *
	 * @var String
	 */
	protected $str_domain_key = 'domain';

	/**
	 * Is domain valid
	 *
	 * @var Boolean
	 */
	protected $bl_domain_valid = false;

	/**
	 * @var Oxy_Controller_Dispatcher_Interface
	 */
	protected $_dispatcher;

	/**
	 * @var Oxy_Controller_Request_Http
	 */
	protected $_request;

	/**
	 * Constructor
	 *
	 * @param array $defaults Defaults for map variables with keys as variable names
	 * @param Oxy_Controller_Dispatcher_Interface $dispatcher Dispatcher object
	 * @param Oxy_Controller_Request_Http $request Request object
	 */
	public function __construct(array $defaults = array(),
								Oxy_Controller_Dispatcher_Interface $dispatcher = null,
								Oxy_Controller_Request_Http $request = null)
	{
		$this->_defaults = $defaults;
		if (isset($request))
		{
			$this->_request = $request;
		}
		if (isset($dispatcher))
		{
			$this->_dispatcher = $dispatcher;
		}
	}

	/**
	 * Override to add domain logic
	 *
	 * @return void
	 */
	protected function _setRequestKeys()
	{
		if (null !== $this->_request)
		{
			// Oxy extending
			$this->str_domain_key = $this->_request->getDomainKey();
			$this->_moduleKey = $this->_request->getModuleKey();
			$this->_controllerKey = $this->_request->getControllerKey();
			$this->_actionKey = $this->_request->getActionKey();
		}
		if (null !== $this->_dispatcher)
		{
			$this->_defaults += array($this->str_domain_key => $this->_dispatcher->getDefaultDomain(),
									  $this->_controllerKey => $this->_dispatcher->getDefaultControllerName(),
									  $this->_actionKey => $this->_dispatcher->getDefaultAction(),
									  $this->_moduleKey => $this->_dispatcher->getDefaultModule());
		}
		$this->_keysSet = true;
	}

	/**
	 * Matches a user submitted path. Assigns and returns an array of variables
	 * on a successful match.
	 *
	 * If a request object is registered, it uses its setDomainName(),setModuleName(),
	 * setControllerName(), and setActionName() accessors to set those values.
	 * Always returns the values as an array.
	 *
	 * @param string $path Path used to match against this routing map
	 * @return array An array of assigned values or a false on a mismatch
	 */
	public function match($path, $partial = false)
	{
		$this->_setRequestKeys();
		$values = array();
		$params = array();
		if (! $partial)
		{
			$path = trim($path, Zend_Controller_Router_Route_Module::URI_DELIMITER);
		}
		else
		{
			$matchedPath = $path;
		}
		if ($path != '')
		{
			$path = explode(Zend_Controller_Router_Route_Module::URI_DELIMITER, $path);

			if ($this->_dispatcher && $this->_dispatcher->isValidDomain($path[0]))
			{
				$values[$this->str_domain_key] = array_shift($path);
			}
			if (count($path) &&
				! empty($path[0]) &&
				$this->_dispatcher &&
				$this->_dispatcher->isValidModule($path[0]))
			{
				$values[$this->_moduleKey] = array_shift($path);
				$this->_moduleValid = true;
			}
			if (count($path) && ! empty($path[0]))
			{
				$values[$this->_controllerKey] = array_shift($path);
			}
			if (count($path) && ! empty($path[0]))
			{
				$values[$this->_actionKey] = array_shift($path);
			}
			if ($numSegs = count($path))
			{
				for ($i = 0; $i < $numSegs; $i = $i + 2)
				{
					$key = urldecode($path[$i]);
					$val = isset($path[$i + 1]) ? urldecode($path[$i + 1]) : null;
					$params[$key] = (isset($params[$key]) ? (array_merge((array) $params[$key], array($val))) : $val);
				}
			}
		}
		if ($partial)
		{
			$this->setMatchedPath($matchedPath);
		}
		$this->_values = $values + $params;
		return $this->_values + $this->_defaults;
	}

	/**
	 * Assembles user submitted parameters forming a URL path defined by this route
	 *
	 * @param array $data An array of variable and value pairs used as parameters
	 * @param bool $reset Weither to reset the current params
	 * @return string Route path with user submitted parameters
	 */
	public function assemble($data = array(), $reset = false, $encode = true, $partial = false)
	{
		if (! $this->_keysSet)
		{
			$this->_setRequestKeys();
		}
		$params = (! $reset) ? $this->_values : array();
		foreach ($data as $key => $value)
		{
			if ($value !== null)
			{
				$params[$key] = $value;
			}
			elseif (isset($params[$key]))
			{
				unset($params[$key]);
			}
		}
		$params += $this->_defaults;
		$url = '';
		if ($this->bl_domain_valid || array_key_exists($this->str_domain_key, $data))
		{
			if ($params[$this->str_domain_key] != $this->_defaults[$this->str_domain_key])
			{
				$str_domain = $params[$this->str_domain_key];
			}
		}
		unset($params[$this->str_domain_key]);

		if ($this->_moduleValid || array_key_exists($this->_moduleKey, $data))
		{
			if ($params[$this->_moduleKey] != $this->_defaults[$this->_moduleKey])
			{
				$module = $params[$this->_moduleKey];
			}
		}
		unset($params[$this->_moduleKey]);

		$controller = $params[$this->_controllerKey];
		unset($params[$this->_controllerKey]);

		$action = $params[$this->_actionKey];
		unset($params[$this->_actionKey]);

		foreach ($params as $key => $value)
		{
			if (is_array($value))
			{
				foreach ($value as $arrayValue)
				{
					if ($encode)
						$arrayValue = urlencode($arrayValue);
					$url .= '/' . $key;
					$url .= '/' . $arrayValue;
				}
			}
			else
			{
				if ($encode)
					$value = urlencode($value);
				$url .= '/' . $key;
				$url .= '/' . $value;
			}
		}
		if (! empty($url) || $action !== $this->_defaults[$this->_actionKey])
		{
			if ($encode)
				$action = urlencode($action);
			$url = '/' . $action . $url;
		}
		if (! empty($url) || $controller !== $this->_defaults[$this->_controllerKey])
		{
			if ($encode)
				$controller = urlencode($controller);
			$url = '/' . $controller . $url;
		}
		if (isset($module))
		{
			if ($encode)
				$module = urlencode($module);
			$url = '/' . $module . $url;
		}
		if (isset($str_domain))
		{
			if ($encode)
				$str_domain = urlencode($str_domain);
			$url = '/' . $str_domain . $url;
		}
		return ltrim($url, Zend_Controller_Router_Route_Module::URI_DELIMITER);
	}
}
?>