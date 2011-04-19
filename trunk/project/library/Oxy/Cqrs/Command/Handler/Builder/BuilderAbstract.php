<?php
/**
 * Base class for commands builder
 *
 * @category Yxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Cqrs_Command_Handler_Builder_BuilderAbstract 
    implements Oxy_Cqrs_Command_Handler_Builder_BuilderInterface
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
                throw new InvalidArgumentException(
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
     * @param Oxy_Cqrs_Command_CommandInterface $command
     * @return Oxy_Cqrs_Command_Handler_HandlerInterface
     */
    public function buildCommandHandlerForCommand(Oxy_Cqrs_Command_CommandInterface $command)
    {
        // Get command name
        $commandName = $command->getCommandName();
        $pos = strpos($commandName, 'Command');
        $len = strlen('Command');
        $commandHandlerName = substr($commandName, 0, $pos)
                            . 'Command_Handler'
                            . substr($commandName, $pos + $len);
        $commandHandlerName = Oxy_Utils_String::underscoreToCamelCase($commandHandlerName);
        // lcfirst is only available since PHP 5.3
        $commandHandlerName = lcfirst($commandHandlerName);
        if (! isset($this->_commandHandlerFactories[$commandHandlerName])) {
            throw new Oxy_Cqrs_Command_Handler_Builder_Exception(
                "No command handler for command '{$commandName}' specified"
            );
        }
        $commandHandlerFactory = $this->_commandHandlerFactories[$commandHandlerName];
        $commandHandler = call_user_func_array(
            $commandHandlerFactory['callback'],
            $commandHandlerFactory['parameters']
        );
        if (!($commandHandler instanceof Oxy_Cqrs_Command_Handler_HandlerInterface)) {
            throw new Oxy_Cqrs_Command_Handler_Builder_Exception(
                "Command handler [{$commandHandlerName}] was not found!"
            );
        }
        
        return $commandHandler;
    }
}