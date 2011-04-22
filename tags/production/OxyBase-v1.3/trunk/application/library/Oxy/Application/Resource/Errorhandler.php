<?php
/**
 * Error handler resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_Errorhandler extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Oxy_Error_Handler
     */
    protected $obj_error_handler;

    /**
     * Initialize Front Controller
     *
     * @return Oxy_Error_Handler
     */
    public function init()
    {
        $this->obj_error_handler = Oxy_Error_Handler::getInstance();

    	foreach ($this->getOptions() as $key => $value)
    	{
            switch (strtolower($key))
            {
                case 'errorsfile':
                    $this->obj_error_handler->init($value);
                    break;
            }
        }

        return $this->obj_error_handler;
    }
}
