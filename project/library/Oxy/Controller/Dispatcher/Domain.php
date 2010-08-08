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
class Oxy_Controller_Dispatcher_Domain extends Oxy_Controller_Dispatcher_Abstract 
{
	/**
	 * Add a single path to the controller directory stack
	 *
	 * @param string $str_path
	 * @param string $str_module
	 * @param string $str_domain
	 *
	 * @return Oxy_Controller_Dispatcher_Domain
	 */
	public function addControllerDirectory($path, $module = null, $domain = null)
	{
		if (null === $module){
			$module = $this->_defaultModule;
		}
		
		if (null === $domain){
			$domain = $this->_defaultDomain;
		}
		$module = (string) $module;
		$domain = (string) $domain;
		$path = rtrim((string) $path, '/\\');
		$this->_controllerDirectory[$domain][$module] = $path;
		return $this;
	}

	/**
	 * Set controller directory
	 *
	 * @param array|string $directory
	 * @param string $module
	 * @param string $domain
	 *
	 * @return Oxy_Controller_Dispatcher_Domain
	 */
	public function setControllerDirectory($directory, $module = null, $domain = null)
	{
		$this->_controllerDirectory = array();
		if (is_string($directory)){
			$this->addControllerDirectory($directory, $module, $domain);
		} elseif (is_array($directory)) {
			foreach ((array) $directory as $domainKey => $modules){
				foreach ((array) $modules as $moduleName => $path){
					$this->addControllerDirectory($path, $moduleName, $domainKey);
				}
			}
		} else {
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
	public function getControllerDirectory($module = null, $domain = null)
	{
		if (null === $domain){
			return $this->_controllerDirectory;
		}
		if (null === $module && $domain !== null){
			return $this->_controllerDirectory[$domain];
		}
		$module = (string) $module;
		$domain = (string) $domain;
		if (array_key_exists($domain, $this->_controllerDirectory)){
			if (array_key_exists($module, $this->_controllerDirectory[$domain])){
				return $this->_controllerDirectory[$domain][$module];
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
	public function removeControllerDirectory($module, $domain)
	{
		$module = (string) $module;
		$domain = (string) $domain;
		if (array_key_exists($domain, $this->_controllerDirectory)){
			if (array_key_exists($module, $this->_controllerDirectory[$domain])){
				unset($this->_controllerDirectory[$domain][$module]);
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
	public function isValidDomain($domain)
	{
		if (! is_string($domain)){
			return false;
		}
		$domain = $this->formatDomainName($domain);
		$controllerDir = $this->getControllerDirectory();
		foreach (array_keys($controllerDir) as $domainName){
			if ($domain == $domainName){
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
	public function isValidModule($module)
	{
		if (! is_string($module)){
			return false;
		}
		$module = strtolower($module);
		$arr_controller_dir = $this->getControllerDirectory();
		foreach ($arr_controller_dir as $arr_domain_modules){
			foreach (array_keys($arr_domain_modules) as $str_module_name){
				if ($module == strtolower($str_module_name)){
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
	public function getControllerClass(Zend_Controller_Request_Abstract $request)
	{
		$controllerName = $request->getControllerName();
		if (empty($controllerName)){
			if (! $this->getParam('useDefaultControllerAlways')){
				return false;
			}
			$controllerName = $this->getDefaultControllerName();
			$request->setControllerName($controllerName);
		}
		$className = $this->formatControllerName($controllerName);
		$controllerDirs = $this->getControllerDirectory();
		$domain = $request->getDomainName();
		$module = $request->getModuleName();
		if ($this->isValidDomain($domain)){
		    $domain = $this->formatDomainName($domain);
			$this->_curDomain = $domain;
			if ($this->isValidModule($module)){
				$this->_curModule = $module;
				$this->_curDirectory = $controllerDirs[$domain][$module];
			} elseif ($this->isValidModule($this->_defaultModule)){
				$request->setModuleName($this->_defaultModule);
				$this->_curModule = $this->_defaultModule;
				$this->_curDirectory = $controllerDirs[$domain][$this->_defaultModule];
			}
		} elseif ($this->isValidDomain($this->_defaultDomain)) {
		    $this->_defaultDomain = $this->formatDomainName($this->_defaultDomain);
			$request->setDomainName($this->_defaultDomain);
			$this->_curDomain = $this->_defaultDomain;
			if ($this->isValidModule($module)) {
				$this->_curModule = $module;
				$this->_curDirectory = $controllerDirs[$this->_curDomain][$module];
			} elseif ($this->isValidModule($this->_defaultModule)) {
				$request->setModuleName($this->_defaultModule);
				$this->_curModule = $this->_defaultModule;
				$this->_curDirectory = $controllerDirs[$this->_curDomain][$this->_defaultModule];
			}
		} else {
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
    	$segments = explode('_', $class);
    	if($this->isValidDomain($segments[0])){
    		array_shift($segments);
    	}
    	if($this->isValidModule($segments[0])){
    		array_shift($segments);
    	}
    	$class = implode('_', $segments);
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
		$domain = $request->getDomainName();
		$module = $request->getModuleName();
		$controllerDirs = $this->getControllerDirectory();
		$this->_curDomain = $this->_defaultDomain;
		$this->_curModule = $this->_defaultModule;
		$this->_curDirectory = $controllerDirs[$this->_defaultDomain][$this->_defaultModule];
		if($this->isValidDomain($domain)){
		    $domain = $this->formatDomainName($domain);
			if ($this->isValidModule($module)){
				$found = false;
				if (class_exists($default, false)){
					$found = true;
				} else {
					$moduleDir = $controllerDirs[$domain][$module];
					$fileSpec = $domain . DIRECTORY_SEPARATOR .
								'interface' . DIRECTORY_SEPARATOR .
								$moduleDir . DIRECTORY_SEPARATOR .
								$this->classToFilename($default);
					if (Zend_Loader::isReadable($fileSpec)){
						$found = true;
						$this->_curDirectory = $moduleDir;
					}
				}
				if ($found){
					$request->setModuleName($module);
					$this->_curModule = $this->formatModuleName($module);
				}
			} else {
				$request->setModuleName($this->_defaultModule);
			}
		} else {
		    $domain = $this->formatDomainName($domain);
			if ($this->isValidModule($module)){
				$found = false;
				if (class_exists($default, false)){
					$found = true;
				} else {
					$moduleDir = $controllerDirs[$domain][$module];
					$fileSpec = $domain . DIRECTORY_SEPARATOR .
								'interface' . DIRECTORY_SEPARATOR .
								$moduleDir . DIRECTORY_SEPARATOR .
								$this->classToFilename($default);
					if (Zend_Loader::isReadable($fileSpec)){
						$found = true;
						$this->_curDirectory = $moduleDir;
					}
				}
				if ($found){
					$request->setDomainName($this->_defaultDomain);
					$request->setModuleName($module);
					$this->_curModule = $this->formatModuleName($module);
				}
			} else {
				$request->setDomainName($this->_defaultDomain);
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
    public function formatClassName($domain, $moduleName, $className)
    {
        return ucfirst($domain) . '_' . ucfirst($moduleName) . '_' . $className;
    }

    
    /**
     * Formats a string into an domain name.
     *
     * @param string $unformatted
     * @return string
     */
    public function formatDomainName($unformatted)
    {
        $formatted = $this->_formatName($unformatted, true);
        return ucfirst(strtolower(substr($formatted, 0, 1))) . substr($formatted, 1);
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
            $finalClass = $this->formatClassName($this->_curDomain,
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
     * Returns TRUE if the Zend_Controller_Request_Abstract object can be
     * dispatched to a controller.
     *
     * Use this method wisely. By default, the dispatcher will fall back to the
     * default controller (either in the module specified or the global default)
     * if a given controller does not exist. This method returning false does
     * not necessarily indicate the dispatcher will not still dispatch the call.
     *
     * @param Zend_Controller_Request_Abstract $action
     * @return boolean
     */
    public function isDispatchable(Zend_Controller_Request_Abstract $request)
    {
        $className = $this->getControllerClass($request);
        if (!$className) {
            return false;
        }

        $finalClass = $className;
        if (($this->_defaultModule != $this->_curModule)
            || $this->getParam('prefixDefaultModule'))
        {
            $finalClass = $this->formatClassName($this->_curDomain, $this->_curModule, $className);
        }
        if (class_exists($finalClass, false)) {
            return true;
        }

        $fileSpec    = $this->classToFilename($className);
        $dispatchDir = $this->getDispatchDirectory();
        $test        = $dispatchDir . DIRECTORY_SEPARATOR . $fileSpec;
        return Zend_Loader::isReadable($test);
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
		if (!$this->isDispatchable($request)){
			$controller = $request->getControllerName();
			if (! $this->getParam('useDefaultControllerAlways') && ! empty($controller)){
				throw new Oxy_Controller_Dispatcher_Exception('Invalid controller specified (' . $request->getControllerName() . ')');
			}
			$className = $this->getDefaultControllerClass($request);

		} else {
			$className = $this->getControllerClass($request);
			if (! $className){
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

		if (! ($controller instanceof Zend_Controller_Action_Interface) && ! ($controller instanceof Zend_Controller_Action)){
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