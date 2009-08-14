<?php
/**
* Oxy currency interface
*
* @category Oxy
* @package Currency
* @author Tomas Bartkus
**/
interface Oxy_Language_Interface
{
	/**
	 * Return language id
	 *
	 * @return Integer
	 */
	public function getId();

	/**
	 * Return language code
	 *
	 * @return String
	 */
	public function getCode();

	/**
	 * Return language ISO3 code
	 *
	 * @return String
	 */
	public function getIso3();

	/**
	 * Return language title
	 *
	 * @return String
	 */
	public function getTitle();

	/**
	 * Return if language is default
	 *
	 * @return Boolean
	 */
	public function isDefault();

	/**
	 * Return if language is visible
	 *
	 * @return Boolean
	 */
	public function isVisible();
}
?>