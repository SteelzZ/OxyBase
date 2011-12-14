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

interface BuilderInterface
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