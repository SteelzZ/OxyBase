<?php
/**
* Oxy CQRS provider
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Framework
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Project_Provider_Cqrs extends Zend_Tool_Framework_Provider_Abstract
{
	/**
	 * Show commands
	 *
	 * @param string $boundedContext
	 * @param string $interface
	 */
	public function showCommands($boundedContext, $interface = null)
	{
	}

	/**
	 * Explain command
	 *
	 * @param string $command
	 */
	public function explainCommand($command)
	{
	}

	/**
	 * Show what event handlers are listening for custom event
	 *
	 * @param string $boundedContext
	 * @param string $domainModule
	 * @param string $aggregateRoot
	 */
	public function showEventsObservers($boundedContext, $domainModule = null, $aggregateRoot = null)
	{
	}

	/**
	 * Show specific event handlers
	 *
	 * @param string $boundedContext
	 * @param string $domainModule
	 * @param string $aggregateRoot
	 */
	public function showEventObservers($eventName)
	{
	}

	/**
	 * Attach event handler to given event
	 *
	 * @param string $eventHandler
	 * @param string $event
	 */
	public function attachEventHandlerToEvent($eventHandler, $event)
	{
	}

	/**
	 * Show available DTO
	 *
	 * @param string $boundedContext
	 * @param string $interface
	 */
	public function showDtos($boundedContext, $interface = null)
	{
	}

	/**
	 * Create new DTO
	 *
	 * @param string $dtoName
	 * @param string $boundedContext
	 * @param string $interface
	 * @param array $options
	 */
	public function createDto($dtoName, $boundedContext, $interface, array $options = array())
	{
	}
}