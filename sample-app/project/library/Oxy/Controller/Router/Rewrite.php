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
     * Add default routes which are used to mimic basic router behaviour
     *
     * @return Oxy_Controller_Router_Rewrite
     */
    public function addDefaultRoutes()
    {
        if (!$this->hasRoute('default')) {
            $dispatcher = $this->getFrontController()->getDispatcher();
            $request = $this->getFrontController()->getRequest();

            require_once 'Oxy/Controller/Router/Route/Domain.php';
            $compat = new Oxy_Controller_Router_Route_Domain(array(), $dispatcher, $request);

            $this->_routes = array_merge(array('default' => $compat), $this->_routes);
        }

        return $this;
    }
    
    /**
     * Retrieve Front Controller
     *
     * @return Oxy_Controller_Front
     */
    public function getFrontController()
    {
        // Used cache version if found
        if (null !== $this->_frontController) {
            return $this->_frontController;
        }

        require_once 'Oxy/Controller/Front.php';
        $this->_frontController = Oxy_Controller_Front::getInstance();
        return $this->_frontController;
    }

    /**
     * Find a matching route to the current PATH_INFO and inject
     * returning values to the Request object.
     *
     * @throws Zend_Controller_Router_Exception
     * @return Zend_Controller_Request_Abstract Request object
     */
    public function route(Zend_Controller_Request_Abstract $request)
    {
        if (!$request instanceof Zend_Controller_Request_Http) {
            require_once 'Zend/Controller/Router/Exception.php';
            throw new Zend_Controller_Router_Exception('Zend_Controller_Router_Rewrite requires a Zend_Controller_Request_Http-based request object');
        }

        if ($this->_useDefaultRoutes) {
            $this->addDefaultRoutes();
        }

		/**
		 *  remap/redirect
		 * @todo remove when wont be needed
		 */

		//check if we have remap route and params
		if (isset($this->_routes['remap']) && $request->getMethod() === 'GET')
		{
			$maping = (object)$this->_routes['remap']->getDefaults();

			// fast parse if we identified anything, aka if we have any fast matches for remap/rewrite
			$fastMatches = $maping->fastmatches;
			$processRemap = false;
			foreach ($fastMatches as $fastMatch)
			{
				if ($this->_matchRules($fastMatch, $request))
				{
					// reparse params to param_{param_name}
					// @todo make reserved params domain,action etc like _domain, _action aka more normal non interfering/colliding
					$reparsedGet = array();
					foreach ($request->getQuery() as $key=>$value)
					{
						$reparsedGet[] = 'param_' . $key . '/' . $value;
					}

					$pathInfo = substr($request->getRequestUri(),0,strpos($request->getRequestUri(), '?'));
					if ($pathInfo)
					{
						$reparsedGet[] = 'pathinfo' . $pathInfo;
					}
					$request->setRequestUri('eshop/environment/redirect/process/' . implode('/', $reparsedGet));
					// got something, let the environment/redirect service handle it
					break 1;
				}
			}
		}
		// --------------------------------------- end of remap/rewrite
		
        $routeMatched = false;
        foreach (array_reverse($this->_routes) as $name => $route) {
			//var_dump($route);
            // TODO: Should be an interface method. Hack for 1.0 BC
            if (method_exists($route, 'isAbstract') && $route->isAbstract()) {
                continue;
            }

            // TODO: Should be an interface method. Hack for 1.0 BC
            if (!method_exists($route, 'getVersion') || $route->getVersion() == 1) {
                $match = $request->getPathInfo();
            } else {
                $match = $request;
            }
            

            if ($params = $route->match($match)) {
                $this->_setRequestParams($request, $params);
                $this->_currentRoute = $name;
                $routeMatched        = true;
                break;
            }
        }

         if (!$routeMatched) {
             require_once 'Zend/Controller/Router/Exception.php';
             throw new Zend_Controller_Router_Exception('No route matched the request', 404);
         }

        if($this->_useCurrentParamsAsGlobal) {
            $params = $request->getParams();
            foreach($params as $param => $value) {
                $this->setGlobalParam($param, $value);
            }
        }
        return $request;
    }

    /**
     * Set Front Controller
     *
     * @param Zend_Controller_Front $controller
     * @return Zend_Controller_Router_Interface
     */
    public function setFrontController(Zend_Controller_Front $controller)
    {
        $this->_frontController = $controller;
        return $this;
    }
    
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

	/**
	 * Used for remap/rewrite
	 * @todo remove together with remap/rewrite stuff
	 * @param array $rules
	 * @param Zend_Controller_Request_Abstract $request
	 * @param bool $completeMatch
	 * @return bool
	 */

	private function _matchRules(array $rules, Zend_Controller_Request_Abstract $request, $completeMatch = false)
	{
		$allMatched = false;
		if ($completeMatch)
		{
			foreach ($rules as $param => $value)
			{
				if ($request->getParam($param) === $value)
				{
					$allMatched = true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			foreach ($rules as $param => $rule)
			{
				if (preg_match('/(' . $rule . ')/U', $request->getParam($param)))
				{
					$allMatched = true;
				}
				else
				{
					return false;
				}
			}
		}
		return $allMatched;
	}
}