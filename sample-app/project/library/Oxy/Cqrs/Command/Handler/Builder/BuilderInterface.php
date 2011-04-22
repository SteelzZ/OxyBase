<?php
/**
 * @category Oxy
 * @package Oxy_Cqrs
 * @author Tomas Bartkus
 */
interface Oxy_Cqrs_Command_Handler_Builder_BuilderInterface
{
    /**
     * Build command handler for command
     *
     * @param Oxy_Cqrs_Command_CommandInterface $command
     * 
     * @return Oxy_Cqrs_Command_Handler_HandlerInterface
     */
    public function buildCommandHandlerForCommand(Oxy_Cqrs_Command_CommandInterface $command);
}