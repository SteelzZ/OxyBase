<?php
/**
 * Oxy_Language_Abstract
 *
 * @category   Oxy
 * @package    Language
 * @copyright  2009 OxyCms
 */

/**
 * System language object
 *
 * @category   Oxy
 * @package    Language
 * @copyright  2009 OxyCms
 */
abstract class Oxy_Language_Abstract
{
	/**
	 * Language Code
	 *
	 * @var String
	 */
	private $strCode;

	/**
	 * Language ID
	 *
	 * @var Integer
	 */
	private $intId;

	/**
	 * Language title
	 *
	 * @var String
	 */
	private $strTitle;

	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setCode('en');
		$this->setId(1);
		$this->setTitle('English');
	}

	/**
	 * Set id
	 *
	 * @param Integer $intId
	 */
	public function setId($intId)
	{
		$this->intId = $intId;
	}

	/**
	 * Set code
	 *
	 * @param String $strCode
	 */
	public function setCode($strCode)
	{
		$this->strCode = $strCode;
	}

	/**
	 * Set title
	 *
	 * @param String $strTitle
	 */
	public function setTitle($strTitle)
	{
		$this->strTitle = $strTitle;
	}

	/**
	 * Get code
	 *
	 * @return String
	 */
	public function getCode()
	{
		return $this->strCode;
	}

	/**
	 * Get id
	 *
	 * @return Integer
	 */
	public function getId()
	{
		return $this->intId;
	}


	/**
	 * Get title
	 *
	 * @return String
	 */
	public function getTitle()
	{
		return $this->strTitle;
	}
}
?>