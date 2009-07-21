<?php
/**
 * Doctrine resource
 *
 * @category   Oxy
 * @package    Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initialize doctrine
     *
     * @return self
     */
    public function init()
    {
    	foreach ($this->getOptions() as $key => $value)
    	{
            switch (strtolower($key))
            {
                case 'dbparams':
                	$arr_params = (array) $value;
                	Doctrine_Manager::getInstance()->openConnection("{$arr_params['driver']}://{$arr_params['user']}:{$arr_params['pass']}@{$arr_params['host']}/{$arr_params['db']}", $arr_params['connectionName'])->setCharset($arr_params['charset']);
                    break;
            }
        }
		Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
    }
}

