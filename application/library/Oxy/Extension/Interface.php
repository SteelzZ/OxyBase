<?php
/**
 * Oxy Extension interface
 *
 * @category Oxy
 * @package Extension
 * @author Tomas Bartkus
 */
interface Oxy_Extension_Interface
{
	/**
	 * Retrun extension name
	 *
	 * @return String
	 */
	public function getName();

	/**
	 * Should we use value returned
	 * name by @see Oxy_Extension_Interface::getName()
	 * as class name ?
	 *
	 * @return Boolean
	 */
	public function useInstantly();
}
?>