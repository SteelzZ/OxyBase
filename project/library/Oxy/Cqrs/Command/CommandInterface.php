<?php
/**
 * Command interface
 *
 * @category Oxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Cqrs_Command_CommandInterface
{
    /**
     * Return command name
     *
     * @return String
     */
    public function getCommandName();
}