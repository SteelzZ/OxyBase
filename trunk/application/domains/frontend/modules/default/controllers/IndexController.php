<?php
/**
* Default controller
*
* @category Modules
* @package Default
* @author Tomas Bartkus
* @version 1.0
**/
class Frontend_Default_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
    	$obj_manager = new Frontend_Default_Model_Manager();
    	$obj_manager->admin_default_manager->test();
    	
    	$obj_test = new Frontend_Default_Model_Brain();
    	$obj_test->test();
    }
}