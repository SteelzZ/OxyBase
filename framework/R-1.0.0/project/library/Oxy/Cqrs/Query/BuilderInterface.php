<?php
/**
 * Abstract DTO Builder
 *
 * @category Oxy
 * @package Oxy_Query
 * @subpackage Query
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Cqrs_Query_BuilderInterface
{
    /**
     * Return DTO object
     *
     * @param Array $args
     *
     * @return mixed
     */
    public function getDto(Array $args = array());
}