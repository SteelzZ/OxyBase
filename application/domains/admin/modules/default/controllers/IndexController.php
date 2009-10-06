<?php
/**
* Admin default module
*
* @category Modules
* @package Default
* @author Tomas Bartkus
* @version 1.0
**/
class Admin_Default_IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{
	    $bootstrap = $this->getInvokeArgs('bootstrap');
	    print_r($bootstrap['bootstrap']->getOptions());
	}
}