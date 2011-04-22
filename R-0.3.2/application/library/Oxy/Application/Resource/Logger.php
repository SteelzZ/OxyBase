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
	/**
	 * Logger registry key
	 *
	 * @var String
	 */
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
        $this->obj_logger = new Zend_Log();
    	foreach ($this->getOptions() as $key => $arr_value)
    	{
            switch (strtolower($key))
            {
                case 'stream':
                    $str_stream = null;
                    $arr_params = (array)$arr_value;
                    if(isset($arr_params['stream_str']) && !empty($arr_params['stream_str']))
                    {
                        $str_stream = $arr_params['stream_str'];
                        $obj_writer = new Zend_Log_Writer_Stream($str_stream);
					    $this->obj_logger->addWriter($obj_writer);
                    }
                    break;
                case 'database':
                    $arr_params = (array)$arr_value;
                    if((isset($arr_params['db_params']) && is_array(($arr_params['db_params']))) &&
                       (isset($arr_params['columns']) && is_array(($arr_params['columns']))))
                    {
                        $arr_db_params = array ('host'     => $arr_params['db_params']['host'],
                                                'username' => $arr_params['db_params']['username'],
                                                'password' => $arr_params['db_params']['password'],
                                                'dbname'   => $arr_params['db_params']['dbname']);

                        $obj_db = Zend_Db::factory($arr_params['db_params']['driver'], $arr_db_params);

                        $arr_column_mapping = array('priority' => $arr_params['columns']['lvl'],
                        							'message' => $arr_params['columns']['msg']);

                        $obj_writer = new Zend_Log_Writer_Db($obj_db,
                        								     $arr_params['db_params']['table'],
                                                             $arr_column_mapping);

					    $this->obj_logger->addWriter($obj_writer);
                    }
                    break;
                case 'firebug':
                    $obj_writer = new Zend_Log_Writer_Firebug();
                    $this->obj_logger->addWriter($obj_writer);
                    break;
                case 'mail':
                    $arr_params = (array)$arr_value;
                    if(isset($arr_params['from']) && !empty($arr_params['from']) &&
                       isset($arr_params['to']) && !empty($arr_params['to']))
                       {

                            $obj_mail = new Zend_Mail();
                            $obj_mail->setFrom($arr_params['from'])
                                     ->addTo($arr_params['to']);

                            $obj_writer = new Zend_Log_Writer_Mail($obj_mail);
                            $obj_writer->setSubjectPrependText($arr_params['subject']);
                            $this->obj_logger->addWriter($obj_writer);
                       }
                    break;
            }
        }

		Zend_Registry::set(self::DEFAULT_REGISTRY_KEY, $this->obj_logger);

		$this->obj_logger->info('Belekur sistemoj gali naudot');
		$this->obj_logger->log('RROR', Zend_Log::ERR);
		return $this->obj_logger;
    }
}
