<?php
/**
 * Zend_Navigation_Page_Mvc extended with domain logic
 *
 * @category Oxy
 * @package Oxy_Navigation
 * @subpackage Page
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_Navigation_Page_Mvc extends Zend_Navigation_Page_Mvc
{
    /**
     * Doman name to use when assembling URL
     *
     * @var string
     */
    protected $_strDomain;

    /**
     * Returns whether page should be considered active or not
     *
     * This method will compare the page properties against the request object
     * that is found in the front controller.
     *
     * @param  bool $recursive  [optional] whether page should be considered
     *                          active if any child pages are active. Default is
     *                          false.
     * @return bool             whether page should be considered active or not
     */
    public function isActive($recursive = false)
    {
        if (! $this->_active)
        {
            $objFront = Oxy_Controller_Front::getInstance();
            $arrReqParams = $objFront->getRequest()->getParams();
            if (! array_key_exists('domain', $arrReqParams))
            {
                $arrReqParams['domain'] = $objFront->getDefaultDomain();
            }
            if (! array_key_exists('module', $arrReqParams))
            {
                $arrReqParams['module'] = $objFront->getDefaultModule();
            }
            $arrMyParams = $this->_params;
            if (null !== $this->_strDomain)
            {
                $arrMyParams['domain'] = $this->_strDomain;
            }
            else
            {
                $arrMyParams['domain'] = $objFront->getDefaultDomain();
            }
            if (null !== $this->_module)
            {
                $arrMyParams['module'] = $this->_module;
            }
            else
            {
                $arrMyParams['module'] = $objFront->getDefaultModule();
            }
            if (null !== $this->_controller)
            {
                $arrMyParams['controller'] = $this->_controller;
            }
            else
            {
                $arrMyParams['controller'] = $objFront->getDefaultControllerName();
            }
            if (null !== $this->_action)
            {
                $arrMyParams['action'] = $this->_action;
            }
            else
            {
                $arrMyParams['action'] = $objFront->getDefaultAction();
            }
            if (count(array_intersect_assoc($arrReqParams, $arrMyParams)) == count($arrMyParams))
            {
                $this->_active = true;
                return true;
            }
        }
        return parent::isActive($recursive);
    }

    /**
     * Returns href for this page
     *
     * This method uses {@link Zend_Controller_Action_Helper_Url} to assemble
     * the href based on the page's properties.
     *
     * @return string  page href
     */
    public function getHref()
    {
        if ($this->_hrefCache)
        {
            return $this->_hrefCache;
        }
        if (null === self::$_urlHelper)
        {
            self::$_urlHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Url');
        }
        $arrParams = $this->getParams();
        if ($strParam = $this->getDomain())
        {
            $arrParams['domain'] = $strParam;
        }
        if ($strParam = $this->getModule())
        {
            $arrParams['module'] = $strParam;
        }
        if ($strParam = $this->getController())
        {
            $arrParams['controller'] = $strParam;
        }
        if ($strParam = $this->getAction())
        {
            $arrParams['action'] = $strParam;
        }

        $url = self::$_urlHelper->url($arrParams, $this->getRoute(), $this->getResetParams());
        return $this->_hrefCache = $url;
    }

    /**
     * Sets domain name to use when assembling URL
     *
     * @see getHref()
     *
     * @param  string|null $strDomain        domain name
     * @return Zend_Navigation_Page_Domain   fluent interface, returns self
     * @throws Zend_Navigation_Exception     if invalid domain name is given
     */
    public function setDomain($strDomain)
    {
        if (null !== $strDomain && ! is_string($strDomain))
        {
            throw new Oxy_Navigation_Exception('Invalid argument: $strModule must be a string or null');
        }
        $this->_strDomain = $strDomain;
        $this->_hrefCache = null;
        return $this;
    }

    /**
     * Returns domain name to use when assembling URL
     *
     * @see getHref()
     *
     * @return string|null  domain name or null
     */
    public function getDomain()
    {
        return $this->_strDomain;
    }

	/**
     * Returns an array representation of the page
     *
     * @return array  associative array containing all page properties
     */
    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            array(
                'action'       => $this->getAction(),
                'controller'   => $this->getController(),
                'module'       => $this->getModule(),
                'domain'       => $this->getDomain(),
                'params'       => $this->getParams(),
                'route'        => $this->getRoute(),
                'reset_params' => $this->getResetParams()
            ));
    }
}
?>