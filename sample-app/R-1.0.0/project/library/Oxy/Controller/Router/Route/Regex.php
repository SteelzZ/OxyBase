<?php

class Oxy_Controller_Router_Route_Regex extends Zend_Controller_Router_Route_Regex
{
    /**
     * Instantiates route based on passed Zend_Config structure
     *
     * @param Zend_Config $config Configuration object
     */
    public static function getInstance(Zend_Config $config)
    {
        $defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
        $map = ($config->map instanceof Zend_Config) ? $config->map->toArray() : array();
        $reverse = (isset($config->reverse)) ? $config->reverse : null;
        return new self($config->route, $defs, $map, $reverse);
    }
    
    public function getVersion() {
        return 2;
    }
    
    /**
     * Matches a user submitted path with a previously defined route.
     * Assigns and returns an array of defaults on a successful match.
     *
     * @param  string $path Path used to match against this routing map
     * @return array|false  An array of assigned values or a false on a mismatch
     */
    public function match($request, $partial = false)
    {
        $path = $request->getRequestUri();
        if (!$partial) {
            $path = trim(urldecode($path), '/');
            $regex = '#^' . $this->_regex . '$#i';
        } else {
            $regex = '#^' . $this->_regex . '#i';
        }

        $res = preg_match($regex, $path, $values);
        
        if ($res === 0) {
            return false;
        }

        if ($partial) {
            $this->setMatchedPath($values[0]);
        }

        // array_filter_key()? Why isn't this in a standard PHP function set yet? :)
        foreach ($values as $i => $value) {
            if (!is_int($i) || $i === 0) {
                unset($values[$i]);
            }
        }

        $this->_values = $values;

        $values   = $this->_getMappedValues($values);
        $defaults = $this->_getMappedValues($this->_defaults, false, true);
        $return   = $values + $defaults;
        
        return $return;
    }
}
