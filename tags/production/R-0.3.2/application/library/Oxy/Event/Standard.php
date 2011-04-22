<?php
/**
 * Oxy event interface
 *
 * @category Oxy
 * @package Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_Event_Standard implements Oxy_Event_Interface, ArrayAccess
{
	/**
	 * Value to process
	 *
	 * @var Mixed
	 */
	protected $mix_value = null;

	/**
	 * Event proccessed ?
	 *
	 * @var Boolean
	 */
	protected $bl_processed = false;

	/**
	 * Observer
	 *
	 * @var StdClass
	 */
	protected $obj_subject = null;

	/**
	 * Event name
	 *
	 * @var String
	 */
	protected $str_name = '';

	/**
	 * Parameters
	 *
	 * @var Array
	 */
	protected $arr_parameters = null;

	/**
	 * Constructs a new Oxy_Event_Interface event
	 *
	 * @param Mixed   $subject      The subject
	 * @param String  $name         The event name
	 * @param Array   $parameters   An array of parameters
	 */
	public function __construct($obj_subject = null, $str_name = '', Array $arr_parameters = array())
	{
		$this->setSubject($obj_subject);
		$this->setName($str_name);
		$this->setParameters($arr_parameters);
	}

	/**
	 * Set subject
	 *
	 * @param StdClass $obj_subject
	 *
	 * @throws Oxy_Event_Exception
	 * @return void
	 */
	public function setSubject($obj_subject = null)
	{
		if(is_null($obj_subject))
		{
			throw new Oxy_Event_Exception('Subject can not be null!');
		}

		$this->obj_subject = $obj_subject;
	}

	/**
	 * Set parameters
	 *
	 * @param Array $arr_parameters
	 *
	 * @return void
	 */
	public function setParameters(Array $arr_parameters = array())
	{
		$this->arr_parameters = $arr_parameters;
	}

	/**
	 * Set event name
	 *
	 * @param String $str_name
	 * @throws Oxy_Event_Exception
	 * @return void
	 */
	public function setName($str_name = '')
	{
		if(is_null($str_name) || empty($str_name))
		{
			throw new Oxy_Event_Exception('Event name can not be null or empty string! - '.$str_name);
		}

		$this->str_name = $str_name;
	}

	/**
	 * Returns the subject.
	 *
	 * @return Mixed The subject
	 */
	public function getSubject()
	{
		return $this->obj_subject;
	}

	/**
	 * Returns the event name.
	 *
	 * @return String The event name
	 */
	public function getName()
	{
		return $this->str_name;
	}

	/**
	 * Sets the return value for this event
	 *
	 * @param Mixed $value The return value
	 */
	public function setReturnValue($mix_value = null)
	{
		$this->mix_value = $mix_value;
	}

	/**
	 * Returns the return value.
	 *
	 * @return Mixed The return value
	 */
	public function getReturnValue()
	{
		return $this->value;
	}

	/**
	 * Sets the processed flag
	 *
	 * @param Boolean $bl_processed The processed flag value
	 */
	public function setProcessed($bl_processed = false)
	{
		$this->bl_processed = (boolean) $bl_processed;
	}

	/**
	 * Returns whether the event has been processed by a listener or not.
	 *
	 * @return Boolean true if the event has been processed, false otherwise
	 */
	public function isProcessed()
	{
		return (boolean)$this->bl_processed;
	}

	/**
	 * Returns the event parameters.
	 *
	 * @return Array The event parameters
	 */
	public function getParameters()
	{
		return $this->arr_parameters;
	}

	/**
	 * Returns true if the parameter exists (implements the ArrayAccess interface).
	 *
	 * @param  String  $str_name  The parameter name
	 *
	 * @return Boolean true if the parameter exists, false otherwise
	 */
	public function offsetExists($str_name)
	{
		return array_key_exists($str_name, $this->arr_parameters);
	}

	/**
	 * Returns a parameter value (implements the ArrayAccess interface).
	 *
	 * @param  Atring  $str_name  The parameter name
	 *
	 * @return Mixed  The parameter value
	 */
	public function offsetGet($str_name)
	{
		if (!array_key_exists($str_name, $this->arr_parameters))
		{
			throw new InvalidArgumentException(sprintf('The event "%s" has no "%s" parameter.', $this->str_name, $str_name));
		}
		return $this->arr_parameters[$str_name];
	}

	/**
	 * Sets a parameter (implements the ArrayAccess interface).
	 *
	 * @param String  $str_name   The parameter name
	 * @param Mixed   $mix_value  The parameter value
	 */
	public function offsetSet($str_name, $mix_value)
	{
		$this->arr_parameters[$str_name] = $mix_value;
	}

	/**
	 * Removes a parameter (implements the ArrayAccess interface).
	 *
	 * @param string $str_name    The parameter name
	 */
	public function offsetUnset($str_name)
	{
		unset($this->arr_parameters[$str_name]);
	}
}
?>