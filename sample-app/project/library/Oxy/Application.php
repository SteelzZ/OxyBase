<?php
/** Zend_Application */
require_once 'Zend/Application.php';

/**
* Oxy Application
*
* @category Oxy
* @package Oxy_Application
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
class Oxy_Application extends Zend_Application {

    /**
     * Cache config?
     */
    protected $_useCache = false;

    /**
     * Default caching options
     */
    protected $_cacheOptions = array(
        'frontendType' => 'Core',
        'backendType' => 'Memcached',
        'frontendOptions' => array(),
        'backendOptions' => array()
    );

	/**
     * Constructor
     *
     * Initialize application. Potentially initializes include_paths, PHP
     * settings, and bootstrap class.
     *
     * @param  string                   $environment
     * @param  string|array|Zend_Config $options String path to configuration file, or array/Zend_Config of configuration options
     * @throws Oxy_Application_Exception When invalid options are provided
     * @return void
     */
    public function __construct($environment, $options = null)
    {
        $this->_environment = (string) $environment;

        require_once 'Oxy/Loader/Autoloader.php';
        $this->_autoloader = Oxy_Loader_Autoloader::getInstance();

        if (null !== $options) {
            $this->_useCache = false;

            if (is_string($options)) {
                $options = $this->_loadConfig($options);
            } elseif ($options instanceof Zend_Config) {
                $options = $options->toArray();
            } elseif (!is_array($options)) {
                throw new Oxy_Application_Exception('Invalid options provided; must be location of config file, a config object, or an array');
            }

            $this->setOptions($options);
        }
    }

    /**
     * Load configuration file of options.
     *
     * Optionally will cache the configuration.
     *
     * @param  string $file
     * @throws Zend_Application_Exception When invalid configuration file is provided
     * @return array
     */
    protected function _loadConfig($file)
    {
        if (!$this->_useCache) {
            return parent::_loadConfig($file);
        }

        $cacheHash = 'config' . md5(serialize($file) . $this->_environment);

        try {
            $cache = Zend_Cache::factory(
                $this->_cacheOptions['frontendType'],
                $this->_cacheOptions['backendType'],
                $this->_cacheOptions['frontendOptions'],
                $this->_cacheOptions['backendOptions']
            );
            $cache->setOption('automatic_serialization', true);

            $config = $cache->load($cacheHash);

            if (!$config) {
                $config = parent::_loadConfig($file);
                $cache->save($config, $cacheHash);
            }

        } catch (Zend_Cache_Exception $e) {
            $config = parent::_loadConfig($file);
        }

        return $config;
    }

}