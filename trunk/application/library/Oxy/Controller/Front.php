<?php

/**
 * Oxy front controller
 *
 * @category Oxy
 * @package Controller
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Controller_Front extends Zend_Controller_Front
{
	/**
	 * Singleton instance
	 *
	 * @return Oxy_Controller_Front
	 */
	public static function getInstance()
	{
		if (null === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Set the dispatcher object.  The dispatcher is responsible for
	 * taking a Zend_Controller_Dispatcher_Token object, instantiating the controller, and
	 * call the action method of the controller.
	 *
	 * @param Oxy_Controller_Dispatcher_Interface $dispatcher
	 * @return Oxy_Controller_Front
	 */
	public function setDispatcher(Oxy_Controller_Dispatcher_Interface $obj_dispatcher)
	{
		$this->_dispatcher = $obj_dispatcher;
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
		if (! $this->_dispatcher instanceof Oxy_Controller_Dispatcher_Interface)
		{
			$this->_dispatcher = new Oxy_Controller_Dispatcher_Domain();
		}
		return $this->_dispatcher;
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
	public function addDomainDirectory($str_path)
	{
		try
		{
			$obj_dir = new DirectoryIterator($str_path);
		}
		catch (Exception $e)
		{
			throw new Oxy_Controller_Exception("Directory $str_path not readable");
		}
		foreach ($obj_dir as $obj_file)
		{
			if ($obj_file->isDot() || ! $obj_file->isDir())
			{
				continue;
			}
			if ($obj_file->isDir())
			{
				$str_domain = $obj_file->getFilename();
				// Don't use SCCS directories as modules
				if (preg_match('/^[^a-z]/i', $str_domain) || ('CVS' == $str_domain))
				{
					continue;
				}

				$str_modules_dir = $obj_file->getPath() . DIRECTORY_SEPARATOR .
								   $str_domain .		  DIRECTORY_SEPARATOR .
								   'modules';

				$obj_modules_dir = new DirectoryIterator($str_modules_dir);

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
					$this->addControllerDirectory($str_module_dir, $str_module, $str_domain);
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
}
?>