<?php
/**
 * Unit of work interface
 *
 * @category Oxy
 * @package Oxy_Utils
 */
namespace Oxy\Utils;
class String
{
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