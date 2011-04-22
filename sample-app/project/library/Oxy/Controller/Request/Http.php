<?php
/**
* Oxy request
*
* @category Oxy
* @package Oxy_Controller
* @subpackage Reuqest
* @author Tomas Bartkus
**/
class Oxy_Controller_Request_Http extends Zend_Controller_Request_Http
{
	/**
     * Domain
     *
     * @var string
     */
    protected $str_domain;

    /**
     * Domain key for retrieving domain from params
     *
     * @var string
     */
    protected $str_domain_key = 'domain';

	/**
     * Retrieve the domain key
     *
     * @return string
     */
    public function getDomainKey()
    {
        return $this->str_domain_key;
    }

	/**
     * Retrieve the domain name
     *
     * @return string
     */
    public function getDomainName()
    {
        if (null === $this->str_domain) {
            $this->str_domain = $this->getParam($this->getDomainKey());
        }

        return $this->str_domain;
    }

    /**
     * Set the domain name to use
     *
     * @param string $str_value
     * @return Zend_Controller_Request_Abstract
     */
    public function setDomainName($str_value)
    {
        $this->str_domain = $str_value;
        return $this;
    }

	/**
     * Set the domain key
     *
     * @param string $str_key
     * @return Zend_Controller_Request_Abstract
     */
    public function setDomainKey($str_key)
    {
        $this->str_domain_key = (string) $str_key;
        return $this;
    }
}
?>