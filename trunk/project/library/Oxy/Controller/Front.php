<?php
/**
 * Oxy front controller
 *
 * @category Oxy
 * @package Oxy_Controller
 * @author Tomas Bartkus
 **/
class Oxy_Controller_Front extends Zend_Controller_Front
{
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Zend_Controller_Front
     */
    protected static $_instance = null;
    
	/**
	 * Singleton instance
	 *
	 * @return Oxy_Controller_Front
	 */
	public static function getInstance()
	{
		if (null === parent::$_instance)
		{
			parent::$_instance = new self();
		}
		return parent::$_instance;
	}

	/**
	 * Set request class/object
	 *
	 * Set the request object.  The request holds the request environment.
	 *
	 * If a class name is provided, it will instantiate it
	 *
	 * @param string|Zend_Controller_Request_Abstract $request
	 * @throws Oxy_Controller_Exception if invalid request class
	 * @return Oxy_Controller_Front
	 */
	public function setRequest($request)
	{
		if (is_string($request))
		{
			if (! class_exists($request))
			{
				Zend_Loader::loadClass($request);
			}
			$request = new $request();
		}
		if (! $request instanceof Zend_Controller_Request_Abstract)
		{
			throw new Oxy_Controller_Exception('Invalid request class');
		}
		$this->_request = $request;
		return $this;
	}

	/**
	 * Return the request object.
	 *
	 * @return null|Zend_Controller_Request_Abstract
	 */
	public function getRequest()
	{
	   if (null == $this->_request) {
            require_once 'Oxy/Controller/Request/Http.php';
            $this->setRequest(new Oxy_Controller_Request_Http());
        }
        
		return $this->_request;
	}

	/**
	 * Add a controller directory to the controller directory stack
	 *
	 * If $args is presented and is a string, uses it for the array key mapping
	 * to the directory specified.
	 *
	 * @param string $directory
	 * @param string $module Optional argument; module with which to associate directory. If none provided, assumes 'default'
	 * @return Zend_Controller_Front
	 * @throws Zend_Controller_Exception if directory not found or readable
	 */
	public function addControllerDirectory($str_directory, $str_module = null, $str_domain)
	{
		$this->getDispatcher()->addControllerDirectory($str_directory, $str_module, $str_domain);
		return $this;
	}

	/**
	 * Set controller directory
	 *
	 * Stores controller directory(ies) in dispatcher. May be an array of
	 * directories or a string containing a single directory.
	 *
	 * @param string|array $directory Path to Zend_Controller_Action controller
	 * classes or array of such paths
	 * @param  string $module Optional module name to use with string $directory
	 * @return Oxy_Controller_Front
	 */
	public function setControllerDirectory($mix_directory, $str_module = null, $str_domain = null)
	{
		$this->getDispatcher()->setControllerDirectory($mix_directory, $str_module, $str_domain);
		return $this;
	}

	/**
	 * Retrieve controller directory
	 *
	 * Retrieves:
	 * - Array of all controller directories if no $name passed
	 * - String path if $name passed and exists as a key in controller directory array
	 * - null if $name passed but does not exist in controller directory keys
	 *
	 * @param  string $str_module Default null
	 * @param  string $str_domain Default null
	 *
	 * @return array|string|null
	 */
	public function getControllerDirectory($str_module = null, $str_domain = null)
	{
		return $this->getDispatcher()->getControllerDirectory($str_module, $str_domain);
	}

	/**
	 * Remove a controller directory by module name
	 *
	 * @param  string $str_module
	 * @param  string $str_domain
	 *
	 * @return bool
	 */
	public function removeControllerDirectory($str_module, $str_domain)
	{
		return $this->getDispatcher()->removeControllerDirectory($str_module, $str_domain);
	}

	/**
	 * Specify a directory as containing domains
	 *
	 * Iterates through the directory, adding any subdirectories as modules;
	 * the subdirectory within each module named after {@link $_moduleControllerDirectoryName}
	 * will be used as the controller directory path.
	 *
	 * @param  string $str_path
	 * @return Zend_Controller_Front
	 */
	public function addDomainDirectory($path)
	{
		try
		{
			$dir = new DirectoryIterator($path);
		}
		catch (Exception $e)
		{
			throw new Oxy_Controller_Exception("Directory $path not readable");
		}
		foreach ($dir as $file)
		{
			if ($file->isDot() || ! $file->isDir())
			{
				continue;
			}
			if ($file->isDir())
			{
				$domain = $file->getFilename();
				// Don't use SCCS directories as modules
				if (preg_match('/^[^a-z]/i', $domain) || ('CVS' == $domain))
				{
					continue;
				}

				$modulesDir = $file->getPath() . DIRECTORY_SEPARATOR .
								   $domain .		  DIRECTORY_SEPARATOR .
								   'interface';

				$obj_modules_dir = new DirectoryIterator($modulesDir);

				foreach ($obj_modules_dir as $obj_module_file)
				{
					if ($obj_module_file->isDot() || ! $obj_module_file->isDir())
					{
						continue;
					}
					$str_module = $obj_module_file->getFilename();
					// Don't use SCCS directories as modules
					if (preg_match('/^[^a-z]/i', $str_module) || ('CVS' == $str_module))
					{
						continue;
					}
					$str_module_dir = $obj_module_file->getPathname() . DIRECTORY_SEPARATOR . $this->getModuleControllerDirectoryName();
					$this->addControllerDirectory($str_module_dir, $str_module, $domain);
				}
			}
		}
		return $this;
	}

	/**
	 * Return the path to a domain directory (but not the controllers directory within)
	 *
	 * @param  string $str_domain
	 * @return string|null
	 */
	public function getDomainDirectory($str_domain = null, $str_module = null)
	{
		if (null === $str_domain)
		{
			$request = $this->getRequest();
			if (null !== $request)
			{
				$str_domain = $this->getRequest()->getDomainName();
			}
			if (empty($str_domain))
			{
				$str_domain = $this->getDispatcher()->getDefaultDomain();
			}
		}

		if (null === $str_module)
		{
			$request = $this->getRequest();
			if (null !== $request)
			{
				$str_module = $this->getRequest()->getModuleName();
			}
			if (empty($str_module))
			{
				$str_module = $this->getDispatcher()->getDefaultModule();
			}
		}

		$str_controller_dir = $this->getControllerDirectory($str_module, $str_domain);
                
		if ((null === $str_controller_dir) || ! is_string($str_controller_dir))
		{
			return null;
		}
                
		return dirname($str_controller_dir);
	}

	/**
     * Set the default domain name
     *
     * @param string $str_domain
     * @return Oxy_Controller_Front
     */
    public function setDefaultDomain($str_domain)
    {
        $obj_dispatcher = $this->getDispatcher();
        $obj_dispatcher->setDefaultDomain($str_domain);
        return $this;
    }

    /**
     * Retrieve the default domain
     *
     * @return string
     */
    public function getDefaultDomain()
    {
        return $this->getDispatcher()->getDefaultDomain();
    }
    
    /**
     * Set router class/object
     *
     * Set the router object.  The router is responsible for mapping
     * the request to a controller and action.
     *
     * If a class name is provided, instantiates router with any parameters
     * registered via {@link setParam()} or {@link setParams()}.
     *
     * @param string|Zend_Controller_Router_Interface $router
     * @throws Oxy_Controller_Exception if invalid router class
     * @return Oxy_Controller_Front
     */
    public function setRouter($router)
    {
        if (is_string($router)) {
            if (!class_exists($router)) {
                require_once 'Zend/Loader.php';
                Zend_Loader::loadClass($router);
            }
            $router = new $router();
        }

        if (!$router instanceof Zend_Controller_Router_Interface) {
            require_once 'Zend/Controller/Exception.php';
            throw new Oxy_Controller_Exception('Invalid router class');
        }

        $router->setFrontController($this);
        $this->_router = $router;

        return $this;
    }

    /**
     * Return the router object.
     *
     * Instantiates a Oxy_Controller_Router_Rewrite object if no router currently set.
     *
     * @return Zend_Controller_Router_Interface
     */
    public function getRouter()
    {
        if (null == $this->_router) {
            require_once 'Oxy/Controller/Router/Rewrite.php';
            $this->setRouter(new Oxy_Controller_Router_Rewrite());
        }

        return $this->_router;
    }
    
    /**
     * Set the dispatcher object.  The dispatcher is responsible for
     * taking a Zend_Controller_Dispatcher_Token object, instantiating the controller, and
     * call the action method of the controller.
     *
     * @param Oxy_Controller_Dispatcher_Interface $dispatcher
     * @return Oxy_Controller_Front
     */
    public function setDispatcher(Oxy_Controller_Dispatcher_Interface $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        return $this;
    }

    /**
     * Return the dispatcher object.
     *
     * @return Oxy_Controller_Dispatcher_Interface
     */
    public function getDispatcher()
    {
        /**
         * Instantiate the default dispatcher if one was not set.
         */
        if (!$this->_dispatcher instanceof Oxy_Controller_Dispatcher_Interface) {
            require_once 'Oxy/Controller/Dispatcher/Domain.php';
            $this->_dispatcher = new Oxy_Controller_Dispatcher_Domain();
        }
        return $this->_dispatcher;
    }
    
    /**
     * Dispatch an HTTP request to a controller/action.
     *
     * @param Zend_Controller_Request_Abstract|null $request
     * @param Zend_Controller_Response_Abstract|null $response
     * @return void|Zend_Controller_Response_Abstract Returns response object if returnResponse() is true
     */
    public function dispatch(Zend_Controller_Request_Abstract $request = null, Zend_Controller_Response_Abstract $response = null)
    {
        if (!$this->getParam('noErrorHandler') && !$this->_plugins->hasPlugin('Zend_Controller_Plugin_ErrorHandler')) {
            // Register with stack index of 100
            require_once 'Zend/Controller/Plugin/ErrorHandler.php';
            $this->_plugins->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(), 100);
        }

        if (!$this->getParam('noViewRenderer') && !Zend_Controller_Action_HelperBroker::hasHelper('viewRenderer')) {
            require_once 'Zend/Controller/Action/Helper/ViewRenderer.php';
            Zend_Controller_Action_HelperBroker::getStack()->offsetSet(-80, new Zend_Controller_Action_Helper_ViewRenderer());
        }

        /**
         * Instantiate default request object (HTTP version) if none provided
         */
        if (null !== $request) {
            $this->setRequest($request);
        } elseif ((null === $request) && (null === ($request = $this->getRequest()))) {
            require_once 'Oxy/Controller/Request/Http.php';
            $request = new Oxy_Controller_Request_Http();
            $this->setRequest($request);
        }

        /**
         * Set base URL of request object, if available
         */
        if (is_callable(array($this->_request, 'setBaseUrl'))) {
            if (null !== $this->_baseUrl) {
                $this->_request->setBaseUrl($this->_baseUrl);
            }
        }

        /**
         * Instantiate default response object (HTTP version) if none provided
         */
        if (null !== $response) {
            $this->setResponse($response);
        } elseif ((null === $this->_response) && (null === ($this->_response = $this->getResponse()))) {
            require_once 'Zend/Controller/Response/Http.php';
            $response = new Zend_Controller_Response_Http();
            $this->setResponse($response);
        }

        /**
         * Register request and response objects with plugin broker
         */
        $this->_plugins
             ->setRequest($this->_request)
             ->setResponse($this->_response);

        /**
         * Initialize router
         */
        $router = $this->getRouter();
        $router->setParams($this->getParams());

        /**
         * Initialize dispatcher
         */
        $dispatcher = $this->getDispatcher();
        $dispatcher->setParams($this->getParams())
                   ->setResponse($this->_response);

        // Begin dispatch
        try {
            /**
             * Route request to controller/action, if a router is provided
             */

            /**
            * Notify plugins of router startup
            */
            $this->_plugins->routeStartup($this->_request);

            try {
                $router->route($this->_request);
            }  catch (Exception $e) {
                if ($this->throwExceptions()) {
                    throw $e;
                }

                $this->_response->setException($e);
            }

            /**
            * Notify plugins of router completion
            */
            $this->_plugins->routeShutdown($this->_request);

            /**
             * Notify plugins of dispatch loop startup
             */
            $this->_plugins->dispatchLoopStartup($this->_request);

            /**
             *  Attempt to dispatch the controller/action. If the $this->_request
             *  indicates that it needs to be dispatched, move to the next
             *  action in the request.
             */
            do {
                $this->_request->setDispatched(true);

                /**
                 * Notify plugins of dispatch startup
                 */
                $this->_plugins->preDispatch($this->_request);

                /**
                 * Skip requested action if preDispatch() has reset it
                 */
                if (!$this->_request->isDispatched()) {
                    continue;
                }

                /**
                 * Dispatch request
                 */
                try {
                    $dispatcher->dispatch($this->_request, $this->_response);
                } catch (Exception $e) {
                    if ($this->throwExceptions()) {
                        throw $e;
                    }
                    $this->_response->setException($e);
                }

                /**
                 * Notify plugins of dispatch completion
                 */
                $this->_plugins->postDispatch($this->_request);
            } while (!$this->_request->isDispatched());
        } catch (Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            $this->_response->setException($e);
        }

        /**
         * Notify plugins of dispatch loop completion
         */
        try {
            $this->_plugins->dispatchLoopShutdown();
        } catch (Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            $this->_response->setException($e);
        }

        if ($this->returnResponse()) {
            return $this->_response;
        }

        $this->_response->sendResponse();
    }
}