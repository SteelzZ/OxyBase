<?php

/**
 * Oxy event handler
 *
 * @category Oxy
 * @package Event
 * @author Tomas Bartkus
 */
class Oxy_Event_Handler
{
	/**
	 * Event listeners
	 *
	 * @var Array
	 */
	protected $arr_listeners = array();

	/**
	 * Attach a listener to a given event name.
	 *
	 * @param String  $str_name      An event name
	 * @param Mixed   $obj_listener  Listener object
	 */
	public function attach($str_name, $obj_listener)
	{
		if (!isset($this->arr_listeners[$str_name]))
		{
			$this->arr_listeners[$str_name] = array();
		}
		$this->arr_listeners[$str_name][] = $obj_listener;
	}

	/**
	 * Detach a listener for a given event name.
	 *
	 * @param string   $name      An event name
	 * @param mixed    $listener  A PHP callable
	 *
	 * @return mixed false if listener does not exist, null otherwise
	 */
	public function detach($str_name, $obj_listener)
	{
		if (!isset($this->arr_listeners[$str_name]))
		{
			return false;
		}
		foreach ($this->arr_listeners[$str_name] as $i => $obj_callable)
		{
			if ($obj_listener === $obj_callable)
			{
				unset($this->arr_listeners[$str_name][$i]);
			}
		}
	}

	/**
	 * Notifies all listeners of a given event.
	 *
	 * @param Oxy_Event_Interface $obj_event A Oxy_Event_Interface instance
	 *
	 * @return Oxy_Event_Interface The Oxy_Event_Interface instance
	 */
	public function notify(Oxy_Event_Interface $obj_event)
	{
		foreach ($this->getListeners($obj_event->getName()) as $obj_listener)
		{
			call_user_func($obj_listener, $obj_event);
		}
		return $obj_event;
	}

	/**
	 * Notifies all listeners of a given event until one returns a non null value.
	 *
	 * @param  Oxy_Event_Interface $event A Oxy_Event_Interface instance
	 *
	 * @return Oxy_Event_Interface The Oxy_Event_Interface instance
	 */
	public function notifyUntil(Oxy_Event_Interface $obj_event)
	{
		foreach ($this->getListeners($obj_event->getName()) as $obj_listener)
		{
			if (call_user_func($obj_listener, $obj_event))
			{
				$obj_event->setProcessed(true);
				break;
			}
		}
		return $obj_event;
	}

	/**
	 * Filters a value by calling all listeners of a given event.
	 *
	 * @param  Oxy_Event_Interface  $obj_event   A Oxy_Event_Interface instance
	 * @param  mixed    $mix_value   The value to be filtered
	 *
	 * @return Oxy_Event_Interface The Oxy_Event_Interface instance
	 */
	public function filter(Oxy_Event_Interface $obj_event, $mix_value)
	{
		foreach ($this->getListeners($obj_event->getName()) as $obj_listener)
		{
			$mix_value = call_user_func_array($obj_listener, array($obj_event , $mix_value));
		}
		$obj_event->setReturnValue($mix_value);
		return $obj_event;
	}

	/**
	 * Returns true if the given event name has some listeners.
	 *
	 * @param  string   $str_name    The event name
	 *
	 * @return Boolean true if some listeners are connected, false otherwise
	 */
	public function hasListeners($str_name)
	{
		if (! isset($this->arr_listeners[$str_name]))
		{
			$this->arr_listeners[$str_name] = array();
		}
		return (boolean) count($this->arr_listeners[$str_name]);
	}

	/**
	 * Returns all listeners associated with a given event name.
	 *
	 * @param  string   $str_name    The event name
	 *
	 * @return array  An array of listeners
	 */
	public function getListeners($str_name)
	{
		if (! isset($this->arr_listeners[$str_name]))
		{
			return array();
		}
		return $this->arr_listeners[$str_name];
	}
}
?>