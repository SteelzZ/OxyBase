<?php
/**
 * JSON plugin
 *
 * @category Oxy
 * @package Oxy_Controller
 * @subpackage Plugin
 * @author Tomas Bartkus
 */
class Oxy_Controller_Plugin_Json extends Zend_Controller_Plugin_Abstract
{

    /**
     * Plugin that performs view actions before action is dispatched
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup (Zend_Controller_Request_Abstract $request)
    {
        // If ajax request set format to json
        if($request->isXmlHttpRequest() === true)
        {
            // If something is set do not overwrite
            if($request->getParam('format', null) == null)
            {
                $request->setParam('format', 'json');
            }
        }
    }

}