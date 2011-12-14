<?php
/**
 * Abstract DTO Builder
 *
 * @category Oxy
 * @package Oxy_Query
 * @subpackage Query
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Cqrs\Query;
use Oxy\Cqrs\Query\BuilderInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * Params
     *
     * @var array
     */
    private $_params;

    /**
     * @var Zend_Cache_Core
     */
    private $_cache;

    /**
     * @var string
     */
    protected $_cacheId;

    /**
     * @param string $frontend optional
     * @param string $backend optional
     * @param array $options optional
     */
    public function __construct($frontendCache = 'Core', $backendCache = 'Memcached', $options = array())
    {
        try {
            $this->_cache = Zend_Cache::factory($frontendCache, $backendCache);
            $this->_cache->setOption('automatic_serialization', true);
        } catch (Zend_Cache_Exception $e) {
            $this->_cache = null;
        }
    }

    /**
     * @return string
     */
    protected function _getCacheId()
    {
        return $this->_cacheId;
    }

    /**
     * Load data from cache
     * 
     * @return mixed
     */
    protected function _loadFromCache()
    {
        if(!is_null($this->_cache)){
            return $this->_cache->load($this->_cacheId);
        } else {
            return false;
        }
    }

    /**
     * Save data to cache
     * 
     * @param array $data Data to save
     */
    protected function _saveToCache($data)
    {
        if(!is_null($this->_cache)){ 
            $this->_cache->save($data, $this->_cacheId);
        }
    }

    /**
     * Init params and initialize new cache id
     *
     * @param Array $args
     */
    protected function _initParams(Array $args)
    {
        $this->_cacheId =  md5(get_class($this) . serialize($args));

        if(!empty($args)){
            foreach ($args as $key => $value){
                $this->$key = $value;
            }
        }
    }

    /**
     * Set params
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $name = '_'.$name;
        $this->_params[$name] = $value;
    }

    /**
     * Get initialized param
     *
     * @param string $name
     *
     * @return string|boolean
     */
    public function __get($name)
    {
        if(isset($this->_params[$name])){
            return $this->_params[$name];
        } else {
            return false;
        }
    }

    /**
     * Check if given parameter is set
     * @param $name Parameter name
     */
    public function __isset($name){
        return isset($this->_params[$name]);
    }

    /**
     * Return DTO object
     *
     * @param Array $args
     *
     * @return mixed
     */
    public function getDto(Array $args = array())
    {
    }
}