<?php
/**
 * @category Oxy
 * @package Oxy_Cqrs
 * @author Tomas Bartkus
 */
namespace Oxy\Cqrs\Command\Handler\Builder;
use Oxy\Cqrs\Command\CommandInterface;
use Oxy\Cqrs\Command\Handler\HandlerInterface;

interface BuilderInterface
{
    /**
     * Build command handler for command
     *
     * @param CommandInterface $command
     * 
     * @return HandlerInterface
     */
    public function buildCommandHandlerForCommand(CommandInterface $command);
}