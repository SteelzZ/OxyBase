<?php

/**
 * Class to handle error data in system
 *
 * @category Oxy
 * @package Error
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Error_Handler
{
	/**
	 * Error state
	 *
	 * @var Integer
	 */
	const STATE_IN_ERROR = 0;
	/**
	 * Completed state
	 *
	 * @var Integer
	 */
	const STATE_COMPLETE = 1;
	/**
	 * Unknown state
	 *
	 * @var Integer
	 */
	const STATE_UNKNOWN = 2;

	/**
	 * In what state action is now
	 * after it's execution
	 *
	 * @var Integer
	 */
	protected $int_state;

	/**
	 * Error code - int value
	 * default value is null
	 *
	 * @var Integer
	 */
	protected $int_error_code;

	/**
	 * Readable error message for users
	 *
	 * @var String
	 */
	protected $str_error_msg;

	/**
	 * Path to the error file
	 *
	 * @var String
	 */
	protected $str_error_file;

	/**
	 * Object to hold loaded XML data
	 *
	 * @var SimpleXMLElement
	 */
	protected $obj_simple_xml;

	/**
	 * All gathered errors
	 *
	 * @var Array
	 */
	protected $arr_errors;

	/**
	 * Singleton instance
	 *
	 * Marked only as protected to allow extension of the class. To extend,
	 * simply override {@link getInstance()}.
	 *
	 * @var Oxy_Error_Handler
	 */
	protected static $obj_instance = null;

	/**
	 * Constructor
	 *
	 * Instantiate using {@link getInstance()};
	 *
	 * @return void
	 */
	protected function __construct ()
	{
		$this->int_error_code = null;
		$this->str_error_msg = null;
		$this->int_state = self::STATE_UNKNOWN;
	}

	/**
	 * Enforce singleton; disallow cloning
	 *
	 * @return void
	 */
	private function __clone ()
	{
	}

	/**
	 * Singleton instance
	 *
	 * @return Oxy_Error_Handler
	 */
	public static function getInstance ()
	{
		if (null === self::$obj_instance)
		{
			self::$obj_instance = new self();
		}
		return self::$obj_instance;
	}

	/**
	 * Initialize
	 *
	 * Array of options:
	 * array('state' => $state, 'err_code' => $error_code, 'err_lbl' => error_msg_label);
	 *
	 * @param $str_error_file - path to the error xml file
	 * @param Array $arr_options - options
	 */
	public function init ($str_error_file = null, Array $arr_options = array())
	{
		$this->arr_errors = array();
		// Load errors
		if ($str_error_file !== null)
		{
			$this->loadErrors($str_error_file);
		}
		// Set options
		if (is_array($arr_options))
		{
			if (isset($arr_options['state']))
			{
				switch ((int) $arr_options['state'])
				{
					case self::STATE_COMPLETE:
						$this->setState(self::STATE_COMPLETE);
						break;
					case self::STATE_IN_ERROR:
						$this->setState(self::STATE_IN_ERROR);
						break;
				}
			}
			if (isset($arr_options['err_code']))
			{
				$this->setErrorCode($arr_options['err_code']);
			}
			if (isset($arr_options['err_lbl']))
			{
				$this->setErrorMsg($arr_options['err_lbl']);
			}
		}
	}

	/**
	 * Load errors from xml file
	 *
	 * @param $str_error_file - path to the error xml file
	 * @throws Oxy_Error_Exception
	 */
	public function loadErrors ($str_error_file = null)
	{
		if (! is_string($str_error_file))
		{
			throw new Oxy_Error_Exception('File path must be a valid string!');
		}
		if (Zend_Loader::isReadable($str_error_file))
		{
			$this->obj_simple_xml = simplexml_load_file($str_error_file);
			if ($this->obj_simple_xml === false)
			{
				throw new Oxy_Error_Exception("Could not load {$str_error_file}! Perhaps XML is not well-formed!");
			}
		}
		else
		{
			throw new Oxy_Error_Exception("{$str_error_file} is not readable! Can not open it!");
		}
	}

	/**
	 * Set state
	 *
	 * @param Integer $int_state
	 * @throws Oxy_Error_Exception
	 */
	public function setState ($int_state = null)
	{
		if ($int_state === null || is_int($int_state))
		{
			$this->int_state = $int_state;
		}
		else
		{
			throw new Oxy_Error_Exception('Param must be integer value or null!');
		}
	}

	/**
	 * Set error code
	 *
	 * @param Integer $int_error_code
	 * @throws Oxy_Error_Exception
	 */
	public function setErrorCode ($int_error_code = null)
	{
		if ($int_error_code === null || is_int($int_error_code))
		{
			$this->int_error_code = $int_error_code;
		}
		else
		{
			throw new Oxy_Error_Exception('Error code must be integer value or null!');
		}
	}

	/**
	 * Set error message
	 *
	 * @param String $str_error_lbl - error mesage label
	 */
	public function setErrorMsg ($str_error_lbl = null)
	{
		if ($str_error_lbl === null || is_string($str_error_lbl))
		{
			$this->str_error_msg = $str_error_lbl;
		}
		else
		{
			throw new Oxy_Error_Exception('Error message must be valid string or null!');
		}
	}

	/**
	 * Set data by given code
	 *
	 * Data will be retrieved from errors file
	 *
	 * @param Integer $int_code
	 * @throws Oxy_Error_Exception
	 */
	public function setData ($int_code = null)
	{
		if ($int_code === null || ! is_int($int_code))
		{
			throw new Oxy_Error_Exception('Error code must be integer value!');
		}
		// Select data
		$arr_elements = $this->obj_simple_xml->xpath("//error/code[.={$int_code}]/parent::*");
		if ((boolean) $arr_elements === false)
		{
			throw new Oxy_Error_Exception('Can not find error data by this code!');
		}
		else
		{
			$obj_error = $arr_elements[0];
			if ($obj_error instanceof SimpleXMLElement)
			{
				$this->setErrorCode($int_code);
				$this->setErrorMsg(Oxy_Translate_Standart::getInstance()->translateString((string) $obj_error->label));
			}
			else
			{
				throw new Oxy_Error_Exception('Object is not an instance of SimpleXMLElement class!');
			}
		}
	}

	/**
	 * Return all errors
	 *
	 * @return Array
	 */
	public function getErrors()
	{
		return $this->arr_errors;
	}

	/**
	 * Add new error to array
	 *
	 * @param Integer $int_state
	 * @param Integer $int_code
	 */
	public function addError ($int_state = null, $int_code = null)
	{
		if ($int_code !== null)
		{
			$this->setData($int_code);
		}

		switch ((int) $int_state)
		{
			case self::STATE_COMPLETE:
				$this->setState(self::STATE_COMPLETE);
				break;
			case self::STATE_IN_ERROR:
				$this->setState(self::STATE_IN_ERROR);
				break;
			case self::STATE_UNKNOWN:
				$this->setState(self::STATE_UNKNOWN);
				break;
			default:
				$this->setState(self::STATE_UNKNOWN);
				break;
		}

		$this->arr_errors[] = array('state' => $this->int_state ,
									'error_code' => $this->int_error_code ,
									'error_msg' => $this->str_error_msg);
	}

	/**
	 * Return JSON encoded data that will be
	 * sent back to GUI
	 *
	 * Data, if needed, will be loaded from error files
	 * by passed error code.
	 *
	 * @param $int_code - error code
	 * @return String
	 */
	public function getJsonResponse ($int_state = null, $int_code = null, $bl_do_encode = false)
	{
		if ($int_code !== null)
		{
			$this->setData($int_code);
		}
		switch ((int) $int_state)
		{
			case self::STATE_COMPLETE:
				$this->setState(self::STATE_COMPLETE);
				break;
			case self::STATE_IN_ERROR:
				$this->setState(self::STATE_IN_ERROR);
				break;
			case self::STATE_UNKNOWN:
				$this->setState(self::STATE_UNKNOWN);
				break;
			default:
				$this->setState(self::STATE_UNKNOWN);
				break;
		}
		$arr_return_data = array('Oxy_Response' => array('state' => $this->int_state , 'error_code' => $this->int_error_code , 'error_msg' => $this->str_error_msg));
		return ($bl_do_encode === true ? Zend_Json::encode($arr_return_data) : $arr_return_data);
	}
}
?>