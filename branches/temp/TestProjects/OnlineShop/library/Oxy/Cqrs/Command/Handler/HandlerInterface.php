<?php
/**
 * Command handler interface
 *
 * @category Oxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Cqrs\Command\Handler;
use Oxy\Cqrs\Command\CommandInterface;

interface HandlerInterface
{
    /**
     * @param CommandInterface $command
     */
    public function execute(CommandInterface $command);
}