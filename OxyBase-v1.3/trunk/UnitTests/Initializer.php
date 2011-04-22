<?php
/**
 * Initiliazer
 *
 * Set up required routines
 */
class Initializer extends Zend_Controller_Plugin_Abstract
{
	/**
     * @var Zend_Controller_Front
     */
    protected $obj_front;

    /**
     * @var string Path to application root
     */
    protected $str_root;

    /**
     * Constructor
     *
     * Initialize environment, root path, and configuration.
     *
     * @param  string $env
     * @param  string|null $root
     * @return void
     */
    public function __construct($str_root = null)
    {
        if (null === $str_root) {
            $str_root = realpath(dirname(__FILE__) . '../application/');
        }
        $this->str_root = $str_root;
    }

    /**
     * Initialize modules
     */
    private function initModules(Zend_Controller_Request_Abstract $request, $obj_config, &$obj_front)
    {
		$obj_request = $request;
		$str_request_uri = $obj_request->getRequestUri();

		if(($int_pos = strpos($str_request_uri, "/admin")) !== false)
		{
			// Remove /admin part from URI
			$str_request_uri = substr_replace($str_request_uri, '', $int_pos, 6);

			// Set request URI without admin part
			$obj_request->setRequestUri($str_request_uri);

			// Set new request object
			$obj_front->setRequest($obj_request);

			// Add modules directory for admin
			$obj_front->addModuleDirectory($obj_config->application->base_include_path.$obj_config->application->admin_modules_dir);
			Zend_Registry::set('isAdminRequest', true);

			$obj_front->setDefaultModule('default')
								->setDefaultControllerName('home')
								->setDefaultAction('start');
		}
		else
		{

			if(($int_pos = strpos($str_request_uri, "/client")) !== false)
			{
				// Remove /sysadmin part from URI
				$str_request_uri = substr_replace($str_request_uri, '', $int_pos, 7);

				// Set request URI without admin part
				$obj_request->setRequestUri($str_request_uri);

				// Set new request object
				$obj_front->setRequest($obj_request);
				//
			}
			// Add modules directory for client (frontend)
			$obj_front->addModuleDirectory($obj_config->application->base_include_path.$obj_config->application->modules_dir);
			Zend_Registry::set('isAdminRequest', false);
		}

		return $obj_request;
    }

	/**
     * Route startup
     *
     * @return void
     */
	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{
		$obj_front = Zend_Controller_Front::getInstance();
        $obj_front->throwExceptions(true);
		require 'runtime/LoadErrorHandler.php';
		require 'runtime/LoadConfig.php';
		require 'runtime/LoadLogger.php';
		require 'runtime/LoadDoctrine.php';
		require 'runtime/LoadAcl.php';
		$request = $this->initModules($request, $obj_config, $obj_front);
		//require 'runtime/LoadModules.php';
		require 'runtime/LoadPlugins.php';
		require 'runtime/LoadView.php';
	}

}