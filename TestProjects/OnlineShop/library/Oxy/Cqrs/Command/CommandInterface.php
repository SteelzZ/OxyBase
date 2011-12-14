<?php
/**
 * Command interface
 *
 * @category Oxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Cqrs\Command;

interface CommandInterface
{
    /**
     * Return command name
     *
     * @return String
     */
    public function getCommandName();
}