<?php
/**
* Oxy dispatcher interface
*
* @category
* @package
* @author Tomas Bartkus
* @version 1.0
**/
interface Oxy_Controller_Dispatcher_Interface extends Zend_Controller_Dispatcher_Interface
{
	/**
     * Retrieve the default domain name
     *
     * @return string
     */
    public function getDefaultDomain();
}
?>