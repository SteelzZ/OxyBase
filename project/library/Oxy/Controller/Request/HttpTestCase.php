<?php

/**
 * Oxy_Controller_Request_HttpTestCase
 *
 * HTTP request object for use with Zend_Controller family.
 *
 * @uses Oxy_Controller_Request_Http
 * @package Oxy_Controller
 * @subpackage Request
 */
class Oxy_Controller_Request_HttpTestCase extends Oxy_Controller_Request_Http
{
    /**
     * Request headers
     * @var array
     */
    protected $_headers = array();

    /**
     * Request method
     * @var string
     */
    protected $_method;

    /**
     * Raw POST body
     * @var string|null
     */
    protected $_rawBody;

    /**
     * Valid request method types
     * @var array
     */
    protected $_validMethodTypes = array(
        'DELETE',
        'GET',
        'HEAD',
        'OPTIONS',
        'POST',
        'PUT',
    );

    /**
     * Clear GET values
     * 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function clearQuery()
    {
        $_GET = array();
        return $this;
    }

    /**
     * Clear POST values
     * 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function clearPost()
    {
        $_POST = array();
        return $this;
    }

    /**
     * Set raw POST body
     * 
     * @param  string $content 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function setRawBody($content)
    {
        $this->_rawBody = (string) $content;
        return $this;
    }

    /**
     * Get RAW POST body
     * 
     * @return string|null
     */
    public function getRawBody()
    {
        return $this->_rawBody;
    }

    /**
     * Clear raw POST body
     * 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function clearRawBody()
    {
        $this->_rawBody = null;
        return $this;
    }

    /**
     * Set a cookie
     * 
     * @param  string $key 
     * @param  mixed $value 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function setCookie($key, $value)
    {
        $_COOKIE[(string) $key] = $value;
        return $this;
    }

    /**
     * Set multiple cookies at once
     * 
     * @param array $cookies 
     * @return void
     */
    public function setCookies(array $cookies)
    {
        foreach ($cookies as $key => $value) {
            $_COOKIE[$key] = $value;
        }
        return $this;
    }

    /**
     * Clear all cookies
     * 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function clearCookies()
    {
        $_COOKIE = array();
        return $this;
    }

    /**
     * Set request method
     * 
     * @param  string $type 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function setMethod($type)
    {
        $type = strtoupper(trim((string) $type));
        if (!in_array($type, $this->_validMethodTypes)) {
            require_once 'Zend/Controller/Exception.php';
            throw new Zend_Controller_Exception('Invalid request method specified');
        }
        $this->_method = $type;
        return $this;
    }

    /**
     * Get request method
     * 
     * @return string|null
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Set a request header
     * 
     * @param  string $key 
     * @param  string $value 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function setHeader($key, $value)
    {
        $key = $this->_normalizeHeaderName($key);
        $this->_headers[$key] = (string) $value;
        return $this;
    }

    /**
     * Set request headers
     * 
     * @param  array $headers 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }
        return $this;
    }

    /**
     * Get request header
     * 
     * @param  string $header 
     * @param  mixed $default 
     * @return string|null
     */
    public function getHeader($header, $default = null)
    {
        $header = $this->_normalizeHeaderName($header);
        if (array_key_exists($header, $this->_headers)) {
            return $this->_headers[$header];
        }
        return $default;
    }

    /**
     * Get all request headers
     * 
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * Clear request headers
     * 
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function clearHeaders()
    {
        $this->_headers = array();
        return $this;
    }

    /**
     * Get REQUEST_URI
     * 
     * @return null|string
     */
    public function getRequestUri()
    {
        return $this->_requestUri;
    }

    /**
     * Normalize a header name for setting and retrieval
     * 
     * @param  string $name 
     * @return string
     */
    protected function _normalizeHeaderName($name)
    {
        $name = strtoupper((string) $name);
        $name = str_replace('-', '_', $name);
        return $name;
    }
}
