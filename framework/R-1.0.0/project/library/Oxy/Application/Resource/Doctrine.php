<?php
/**
 * Doctrine resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initialize doctrine
     * Open new connection
     *
     * @return void
     */
    public function init()
    {
    	foreach ($this->getOptions() as $strKey => $strValue)
    	{
            switch (strtolower($strKey))
            {
                case 'dbparams':
                	$arrParams = (array) $strValue;
                	Doctrine_Manager::getInstance()->openConnection("{$arrParams['driver']}://{$arrParams['user']}:{$arrParams['pass']}@{$arrParams['host']}/{$arrParams['db']}", $arrParams['connectionName'])->setCharset($arrParams['charset']);
                    break;
            }
        }
		Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
    }
}

