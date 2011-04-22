<?php
/**
 * Oxy base dispatcher
 *
 * @category Oxy
 * @package Controller
 * @subpackage Dispatcher
 * @author Tomas Bartkus
 * @version 1.0
 **/
abstract class Oxy_Controller_Dispatcher_Abstract extends Zend_Controller_Dispatcher_Standard implements 
    Oxy_Controller_Dispatcher_Interface
{
    /**
	 * @var string
	 */
	protected $_defaultDomain = 'frontend';

	/**
	 * Current domain (formatted)
	 *
	 * @var string
	 */
	protected $_curDomain;
	
	/**
	 * Constructor: Set current domain to default value
	 *
	 * @param  array $params
	 * @return void
	 */
	public function __construct(array $params = array())
	{
		parent::__construct($params);
		$this->_defaultDomain = $this->getDefaultDomain();
	}
	
	/**
	 * Retrieve the default domain
	 *
	 * @return string
	 */
	public function getDefaultDomain()
	{
	    $this->_defaultDomain = $this->formatDomainName($this->_defaultDomain);
		return $this->_defaultDomain;
	}

	/**
	 * Set the default domain
	 *
	 * @param string $domain
	 * @return Oxy_Controller_Dispatcher_Abstract
	 */
	public function setDefaultDomain($domain)
	{
		$this->_defaultDomain = (string)$domain;
		return $this;
	}
}