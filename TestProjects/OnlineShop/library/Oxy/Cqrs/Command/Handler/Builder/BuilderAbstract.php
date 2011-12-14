<?php
/**
 * Base class for commands builder
 *
 * @category Yxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Cqrs\Command\Handler\Builder;
use Oxy\Cqrs\Command\Handler\Builder\BuilderInterface;
use Oxy\Cqrs\Command\CommandInterface;
use Oxy\Utils\String;
use Oxy\Cqrs\Command\Handler\HandlerInterface;
use Oxy\Cqrs\Command\Handler\Builder\Exception;


abstract class BuilderAbstract implements BuilderInterface
{
	/**
     * [Command Handler name] => array(
     * 'callback' => [Callback],
     * 'parameters' => array(
     * [parameter name] => [parameter value],
     * ...
     * ),
     * ),
     *
     * @var array
     */
    private $_commandHandlerFactories = array();

    /**
     * @param array $commandHandlerFactories Array in format:
     * array(
     * [Command Handler name] => array(
     * 'callback' => [Callback],
     * 'parameters' => array(
     * [parameter name] => [parameter value],
     * ...
     * ),
     * ),
     * ...
     * )
     */
    public function __construct(array $commandHandlerFactories)
    {
        foreach ($commandHandlerFactories as $commandHandlerName => $callbackData) {
            $callback = $callbackData['callback'];
            $parameters = $callbackData['parameters'];
            if (! is_array($parameters)) {
                throw new \InvalidArgumentException(
                    "Parameters specified for command handler '{$commandHandlerName}' are not an array"
                );
            }
            $this->_commandHandlerFactories[$commandHandlerName] = array(
                'callback' => $callback,
                'parameters' => $parameters
            );
        }
    }

    /**
     * Build command handler for command
     *
     * @param CommandInterface $command
     * @return HandlerInterface
     */
    public function buildCommandHandlerForCommand(CommandInterface $command)
    {
        // Get command name
        $commandName = $command->getCommandName();
        $pos = strpos($commandName, 'Command');
        $len = strlen('Command');
        $commandHandlerName = substr($commandName, 0, $pos)
                            . 'Command_Handler'
                            . substr($commandName, $pos + $len);
        $commandHandlerName = String::underscoreToCamelCase($commandHandlerName);
        // lcfirst is only available since PHP 5.3
        $commandHandlerName = lcfirst($commandHandlerName);
        if (! isset($this->_commandHandlerFactories[$commandHandlerName])) {
            throw new Exception(
                "No command handler for command '{$commandName}' specified"
            );
        }
        $commandHandlerFactory = $this->_commandHandlerFactories[$commandHandlerName];
        $commandHandler = call_user_func_array(
            $commandHandlerFactory['callback'],
            $commandHandlerFactory['parameters']
        );
        if (!($commandHandler instanceof HandlerInterface)) {
            throw new Exception(
                "Command handler [{$commandHandlerName}] was not found!"
            );
        }
        
        return $commandHandler;
    }
}