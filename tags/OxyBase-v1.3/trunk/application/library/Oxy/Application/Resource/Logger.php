<?php
/**
 * Logger resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_Logger extends Zend_Application_Resource_ResourceAbstract
{
	const DEFAULT_REGISTRY_KEY = 'obj_logger';

    /**
     * @var Zend_Log
     */
    protected $obj_logger;

    /**
     * Initialize logger
     *
     * @return void
     */
    public function init()
    {
        return $this->getLogger();
    }

    /**
     * Retrieve logger
     *
     * @return Zend_Logger
     */
    public function getLogger()
    {
    	foreach ($this->getOptions() as $key => $value)
    	{
            switch (strtolower($key))
            {
                case 'logfile':
                	$obj_writer = new Zend_Log_Writer_Stream($value);
                	$this->obj_logger = new Zend_Log();
					$this->obj_logger->addWriter($obj_writer);
                    break;
            }
        }

		Zend_Registry::set(self::DEFAULT_REGISTRY_KEY, $this->obj_logger);

		return $this->obj_logger;
    }
}
