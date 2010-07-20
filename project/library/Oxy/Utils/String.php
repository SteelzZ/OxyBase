<?php
/**
 * Unit of work interface
 *
 * @category Oxy
 * @package Oxy_Utils
 */
class Oxy_Utils_String
{
    private static $_dashToCamelCaseInflector = null;

    private static $_unserscoreToCamelCaseInflector = null;

    /**
     * Check if string begins with specified prefex.
     *
     * @param string $string
     * @param string $prefex
     * @return bool
     */
    static public function beginsWith($string, $prefex)
    {
        $prefexLength = strlen($prefex);
        $realPrefex = substr($string, 0, $prefexLength);
        $result = $prefex == $realPrefex;
        return $result;
    }

    /**
     * Dash to camelCase
     *
     * @param string $string
     *
     * @return string
     */
    static public function dashToCamelCase($string)
    {
        if (self::$_dashToCamelCaseInflector === null) {
            self::$_dashToCamelCaseInflector = new Zend_Filter_Inflector(':string');
            self::$_dashToCamelCaseInflector->setFilterRule('string', 'Word_DashToCamelCase');
        }
        $result = self::$_dashToCamelCaseInflector->filter(array('string' => $string));
        return $result;
    }

    /**
     * Underscore to camelCase
     *
     * @param string $string
     *
     * @return string
     */
    static public function underscoreToCamelCase($string)
    {
        if (self::$_unserscoreToCamelCaseInflector === null) {
            self::$_unserscoreToCamelCaseInflector = new Zend_Filter_Inflector(':string');
            self::$_unserscoreToCamelCaseInflector->setFilterRule('string', 'Word_UnderscoreToCamelCase');
        }
        $result = self::$_unserscoreToCamelCaseInflector->filter(array('string' => $string));
        return $result;
    }

    /**
     * (almost)safe serialize
     * replace null byte with ~~NULL_BYTE~~ for object serialization
     * @param object|string $param
     * @return string
     */
    static public function serialize($param) {
        return str_replace("\0", "~~NULL_BYTE~~", serialize($param));
    }

    /**
     * (almost)safe unserialize
     * replace ~~NULL_BYTE~~ with null byte for object serialization
     * @param string $param
     * @return object|string
     */
    static public function unserialize($param) {
        return unserialize(str_replace('~~NULL_BYTE~~', "\0", $param));
    }
}