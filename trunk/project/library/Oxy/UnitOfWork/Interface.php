<?php
/**
 * Unit of work interface
 *
 * @category Oxy
 * @package Oxy_UnitOfWork
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_UnitOfWork_Interface
{
    /**
     * Commit work
     *
     * @return void
     */
    public function commit();

    /**
     * Rollback work
     *
     * @return void
     */
    public function rollback();
}