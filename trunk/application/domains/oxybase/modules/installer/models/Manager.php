<?php
/**
 * Installer manager (Facade)
 *
 * @category Oxybase
 * @package Installer
 * @subpackage Model
 * @author Tomas Bartkus
 */
class Oxybase_Installer_Model_Manager extends Oxy_Extension_Accessor implements Oxy_Extension_Interface
{
	/**
	 *
	 */
	public function getName()
	{
		return 'oxybase_installer_manager';
	}

	/**
	 *
	 */
	public function useInstantly()
	{
		return false;
	}

}
?>