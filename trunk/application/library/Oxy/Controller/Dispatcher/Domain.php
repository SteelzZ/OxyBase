<?php

/**
 * Oxy domain dispatcher
 * Dispatcher that understand domain logic
 *
 * @category Oxy
 * @package Controller
 * @subpackage Dispatcher
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Controller_Dispatcher_Domain extends Zend_Controller_Dispatcher_Standard implements Oxy_Controller_Dispatcher_Interface
{
	/**
	 * Default domain
	 *
	 * @var string
	 */
	protected $str_default_domain = 'frontend';

	/**
	 * Current domain (formatted)
	 *
	 * @var string
	 */
	protected $str_cur_domain;

	/**
	 * Constructor: Set current module to default value
	 *
	 * @param  array $params
	 * @return void
	 */
	public function __construct(array $params = array())
	{
		parent::__construct($params);
		$this->str_default_domain = $this->getDefaultDomain();
	}

	/**
	 * Retrieve the default domain
	 *
	 * @return string
	 */
	public function getDefaultDomain()
	{
		return $this->str_default_domain;
	}

	/**
	 * Set the default domain
	 *
	 * @param string $str_domain
	 * @return Zend_Controller_Dispatcher_Abstract
	 */
	public function setDefaultDomain($str_domain)
	{
		$this->str_default_domain = (string) $str_domain;
		return $this;
	}

	/**
	 * Add a single path to the controller directory stack
	 *
	 * @param string $str_path
	 * @param string $str_module
	 * @param string $str_domain
	 *
	 * @return Oxy_Controller_Dispatcher_Domain
	 */
	public function addControllerDirectory($str_path, $str_module = null, $str_domain = null)
	{
		if (null === $str_module)
		{
			$str_module = $this->_defaultModule;
		}
		if (null === $str_domain)
		{
			$str_domain = $this->str_default_domain;
		}
		$str_module = (string) $str_module;
		$str_domain = (string) $str_domain;
		$str_path = rtrim((string) $str_path, '/\\');
		$this->_controllerDirectory[$str_domain][$str_module] = $str_path;
		return $this;
	}

	/**
	 * Set controller directory
	 *
	 * @param array|string $mix_directory
	 * @param string $str_module
	 * @param string $str_domain
	 *
	 * @return Oxy_Controller_Dispatcher_Domain
	 */
	public function setControllerDirectory($mix_directory, $str_module = null, $str_domain = null)
	{
		$this->_controllerDirectory = array();
		if (is_string($mix_directory))
		{
			$this->addControllerDirectory($mix_directory, $str_module, $str_domain);
		}
		elseif (is_array($mix_directory))
		{
			foreach ((array) $mix_directory as $str_domain_key => $arr_module)
			{
				foreach ((array) $arr_module as $str_module_name => $path)
				{
					$this->addControllerDirectory($path, $str_module_name, $str_domain_key);
				}
			}
		}
		else
		{
			throw new Oxy_Controller_Exception('Controller directory spec must be either a string or an array');
		}
		return $this;
	}

	/**
	 * Return the currently set directories for Zend_Controller_Action class
	 * lookup
	 *
	 * If a module is specified, returns just that directory.
	 * If a domain is specified, returns just that directory.
	 *
	 * @param  string $module Module name
	 * @param  string $str_domain Domain name
	 *
	 * @return array|string Returns array of all directories by default, single
	 *
	 * module directory if module argument provided
	 */
	public function getControllerDirectory($str_module = null, $str_domain = null)
	{
		if (null === $str_domain)
		{
			return $this->_controllerDirectory;
		}
		if (null === $str_module && $str_domain !== null)
		{
			return $this->_controllerDirectory[$domain];
		}
		$str_module = (string) $str_module;
		$str_domain = (string) $str_domain;
		if (array_key_exists($str_domain, $this->_controllerDirectory))
		{
			if (array_key_exists($str_module, $this->_controllerDirectory[$str_domain]))
			{
				return $this->_controllerDirectory[$str_domain][$str_module];
			}
		}
		return null;
	}

	/**
	 * Remove a controller directory by module name
	 *
	 * @param  string $str_module
	 * @param  string $str_domain
	 * @return bool
	 */
	public function removeControllerDirectory($str_module, $str_domain)
	{
		$str_module = (string) $str_module;
		$str_domain = (string) $str_domain;
		if (array_key_exists($str_domain, $this->_controllerDirectory))
		{
			if (array_key_exists($str_module, $this->_controllerDirectory[$str_domain]))
			{
				unset($this->_controllerDirectory[$str_domain][$str_module]);
				return true;
			}
		}
		return false;
	}

	/**
	 * Determine if a given module is valid
	 *
	 * @param  string $module
	 * @return bool
	 */
	public function isValidDomain($str_domain)
	{
		if (! is_string($str_domain))
		{
			return false;
		}
		$str_domain = strtolower($str_domain);
		$arr_controller_dir = $this->getControllerDirectory();
		foreach (array_keys($arr_controller_dir) as $str_domain_name)
		{
			if ($str_domain == strtolower($str_domain_name))
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Determine if a given module is valid
	 *
	 * @param  string $module
	 * @return bool
	 */
	public function isValidModule($str_module)
	{
		if (! is_string($str_module))
		{
			return false;
		}
		$str_module = strtolower($str_module);
		$arr_controller_dir = $this->getControllerDirectory();
		foreach ($arr_controller_dir as $arr_domain_modules)
		{
			foreach (array_keys($arr_domain_modules) as $str_module_name)
			{
				if ($str_module == strtolower($str_module_name))
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Get controller class name
	 *
	 * Try request first; if not found, try pulling from request parameter;
	 * if still not found, fallback to default
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @return string|false Returns class name on success
	 */
	public function getControllerClass(Zend_Controller_Request_Abstract $obj_request)
	{
		$controllerName = $obj_request->getControllerName();
		if (empty($controllerName))
		{
			if (! $this->getParam('useDefaultControllerAlways'))
			{
				return false;
			}
			$controllerName = $this->getDefaultControllerName();
			$obj_request->setControllerName($controllerName);
		}
		$className = $this->formatControllerName($controllerName);
		$controllerDirs = $this->getControllerDirectory();
		$str_domain = $obj_request->getDomainName();
		$module = $obj_request->getModuleName();
		if ($this->isValidDomain($str_domain))
		{
			$this->str_cur_domain = $str_domain;
			if ($this->isValidModule($module))
			{
				$this->_curModule = $module;
				$this->_curDirectory = $controllerDirs[$str_domain][$module];
			}
			elseif ($this->isValidModule($this->_defaultModule))
			{
				$obj_request->setModuleName($this->_defaultModule);
				$this->_curModule = $this->_defaultModule;
				$this->_curDirectory = $controllerDirs[$str_domain][$this->_defaultModule];
			}
		}
		elseif ($this->isValidDomain($this->str_default_domain))
		{
			$obj_request->setDomainName($this->str_default_domain);
			$this->str_cur_domain = $this->str_default_domain;
			if ($this->isValidModule($module))
			{
				$this->_curModule = $module;
				$this->_curDirectory = $controllerDirs[$this->str_default_domain][$module];
			}
			elseif ($this->isValidModule($this->_defaultModule))
			{
				$obj_request->setModuleName($this->_defaultModule);
				$this->_curModule = $this->_defaultModule;
				$this->_curDirectory = $controllerDirs[$this->str_default_domain][$this->_defaultModule];
			}
		}
		else
		{
			throw new Oxy_Controller_Exception('No default module defined for this application');
		}

		return $className;
	}

	/**
     * Convert a class name to a filename
     *
     * @param string $class
     * @return string
     */
    public function classToFilename($class)
    {
    	$arr_segments = explode('_', $class);
    	if($this->isValidDomain($arr_segments[0]))
    	{
    		array_shift($arr_segments);
    	}
    	if($this->isValidModule($arr_segments[0]))
    	{
    		array_shift($arr_segments);
    	}
    	$class = implode('_', $arr_segments);
        return str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    }

	/**
	 * Retrieve default controller class
	 *
	 * Determines whether the default controller to use lies within the
	 * requested module, or if the global default should be used.
	 *
	 * By default, will only use the module default unless that controller does
	 * not exist; if this is the case, it falls back to the default controller
	 * in the default module.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @return string
	 */
	public function getDefaultControllerClass(Zend_Controller_Request_Abstract $request)
	{
		$controller = $this->getDefaultControllerName();
		$default = $this->formatControllerName($controller);
		$request->setControllerName($controller)->setActionName(null);
		$str_domain = $request->getDomainName();
		$module = $request->getModuleName();
		$controllerDirs = $this->getControllerDirectory();
		$this->str_cur_domain = $this->str_default_domain;
		$this->_curModule = $this->_defaultModule;
		$this->_curDirectory = $controllerDirs[$this->str_default_domain][$this->_defaultModule];
		if($this->isValidDomain($str_domain))
		{
			if ($this->isValidModule($module))
			{
				$found = false;
				if (class_exists($default, false))
				{
					$found = true;
				}
				else
				{
					$moduleDir = $controllerDirs[$str_domain][$module];
					$fileSpec = $str_domain . DIRECTORY_SEPARATOR .
								'modules' . DIRECTORY_SEPARATOR .
								$moduleDir . DIRECTORY_SEPARATOR .
								$this->classToFilename($default);
					if (Zend_Loader::isReadable($fileSpec))
					{
						$found = true;
						$this->_curDirectory = $moduleDir;
					}
				}
				if ($found)
				{
					$request->setModuleName($module);
					$this->_curModule = $this->formatModuleName($module);
				}
			}
			else
			{

				$request->setModuleName($this->_defaultModule);
			}
		}
		else
		{
			if ($this->isValidModule($module))
			{
				$found = false;
				if (class_exists($default, false))
				{
					$found = true;
				}
				else
				{
					$moduleDir = $controllerDirs[$str_domain][$module];
					$fileSpec = $str_domain . DIRECTORY_SEPARATOR .
								'modules' . DIRECTORY_SEPARATOR .
								$moduleDir . DIRECTORY_SEPARATOR .
								$this->classToFilename($default);
					if (Zend_Loader::isReadable($fileSpec))
					{
						$found = true;
						$this->_curDirectory = $moduleDir;
					}
				}
				if ($found)
				{
					$request->setDomainName($this->str_default_domain);
					$request->setModuleName($module);
					$this->_curModule = $this->formatModuleName($module);
				}
			}
			else
			{
				$request->setDomainName($this->str_default_domain);
				$request->setModuleName($this->_defaultModule);
			}
		}
		return $default;
	}

	/**
     * Format action class name
     *
     * @param string $str_domain Name of the current domain
     * @param string $moduleName Name of the current module
     * @param string $className Name of the action class
     * @return string Formatted class name
     */
    public function formatClassName($str_domain, $moduleName, $className)
    {
        return ucfirst($str_domain) . '_' . ucfirst($moduleName) . '_' . $className;
    }

	/**
     * Load a controller class
     *
     * Attempts to load the controller class file from
     * {@link getControllerDirectory()}.  If the controller belongs to a
     * module, looks for the module prefix to the controller class.
     *
     * @param string $className
     * @return string Class name loaded
     * @throws Oxy_Controller_Dispatcher_Exception if class not loaded
     */
    public function loadClass($className)
    {
        $finalClass  = $className;
        if (($this->_defaultModule != $this->_curModule)
            || $this->getParam('prefixDefaultModule'))
        {
            $finalClass = $this->formatClassName($this->str_cur_domain,
            									 $this->_curModule,
            									 $className);
        }

        if (class_exists($finalClass, false)) {
            return $finalClass;
        }

        $dispatchDir = $this->getDispatchDirectory();
        $loadFile    = $dispatchDir . DIRECTORY_SEPARATOR . $this->classToFilename($className);

        if (!include_once $loadFile) {
            throw new Oxy_Controller_Dispatcher_Exception('Cannot load controller class "' . $className . '" from file "' . $loadFile . "'");
        }

        if (!class_exists($finalClass, false)) {
            throw new Oxy_Controller_Dispatcher_Exception('Invalid controller class ("' . $finalClass . '")');
        }

        return $finalClass;
    }

	/**
	 * Dispatch to a controller/action
	 *
	 * By default, if a controller is not dispatchable, dispatch() will throw
	 * an exception. If you wish to use the default controller instead, set the
	 * param 'useDefaultControllerAlways' via {@link setParam()}.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @param Zend_Controller_Response_Abstract $response
	 * @return void
	 * @throws Oxy_Controller_Dispatcher_Exception
	 */
	public function dispatch(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response)
	{
		$this->setResponse($response);
		/**
		 * Get controller class
		 */
		if (! $this->isDispatchable($request))
		{
			$controller = $request->getControllerName();
			if (! $this->getParam('useDefaultControllerAlways') && ! empty($controller))
			{
				throw new Oxy_Controller_Dispatcher_Exception('Invalid controller specified (' . $request->getControllerName() . ')');
			}
			$className = $this->getDefaultControllerClass($request);

		}
		else
		{
			$className = $this->getControllerClass($request);
			if (! $className)
			{
				$className = $this->getDefaultControllerClass($request);
			}
		}

		/**
		 * Load the controller class file
		 */
		$className = $this->loadClass($className);
		/**
		 * Instantiate controller with request, response, and invocation
		 * arguments; throw exception if it's not an action controller
		 */
		$controller = new $className($request, $this->getResponse(), $this->getParams());

		if (! ($controller instanceof Zend_Controller_Action_Interface) && ! ($controller instanceof Zend_Controller_Action))
		{
			throw new Oxy_Controller_Dispatcher_Exception('Controller "' . $className . '" is not an instance of Zend_Controller_Action_Interface');
		}
		/**
		 * Retrieve the action name
		 */
		$action = $this->getActionMethod($request);
		/**
		 * Dispatch the method call
		 */
		$request->setDispatched(true);
		// by default, buffer output
		$disableOb = $this->getParam('disableOutputBuffering');
		$obLevel = ob_get_level();
		if (empty($disableOb))
		{
			ob_start();
		}
		try
		{
			$controller->dispatch($action);
		}
		catch (Exception $e)
		{
			// Clean output buffer on error
			$curObLevel = ob_get_level();
			if ($curObLevel > $obLevel)
			{
				do
				{
					ob_get_clean();
					$curObLevel = ob_get_level();
				}
				while ($curObLevel > $obLevel);
			}
			throw $e;
		}
		if (empty($disableOb))
		{
			$content = ob_get_clean();
			$response->appendBody($content);
		}
		// Destroy the page controller instance and reflection objects
		$controller = null;
	}
}
?>