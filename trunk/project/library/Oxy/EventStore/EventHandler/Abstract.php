<?php
/**
 * Events publisher base class
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_EventHandler abstract
 * @author Martynas
 */
abstract class Oxy_EventStore_EventHandler_Abstract implements Oxy_EventStore_EventHandler_Interface
{
	private $_logFacility = null;


	final public function attach($service)
	{
		$this->_logFacility = $service;
	}
	/**
	 * logs event handler exception
	 * converts exception to It_Domain_Log_ErrorLog_EventHandlerErrorEntry if needed
	 * @param Exception $e
	 */
	final public function log(Exception $e, Msc_Domain_Event_Interface $event)
	{
		var_dump($this);
		if (!$this->_logFacility)
		{
			throw new Msc_Exception('It service is not defined ' . get_class());
		}
		$date = date('Y-m-d H:i:s');
		$boundedContext = substr(get_class($event), 0, strpos(get_class($event), '_'));
		$errorEntryBaseData = new It_Domain_Log_ErrorLog_EntryBaseData(
							$boundedContext,
							$e->getMessage(),
							$e->getTraceAsString(),
							$date
		);
		$errorEntry = new It_Domain_Log_ErrorLog_EventHandlerErrorEntry(
					'e_handlername', $errorEntryBaseData);

		$this->_logFacility->logEventHandlerError($errorEntry);
	}
}