<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage EventHandler
 */
abstract class Account_Lib_EventHandler_Logger 
    extends Oxy_EventStore_EventHandler_AbstractMongo 
{    
    const ERROR_LEVEL_INFO = 'info';
    const ERROR_LEVEL_WARRNING = 'warrning';
    const ERROR_LEVEL_ERROR = 'error';
    const ERROR_LEVEL_EXCEPTION = 'exception';
    
    /**
     * @param string $where
     * @param string $errorLvl
     * @param array $data
     * @param string $additionalMessage
     */
    protected function _log(
        $where, 
        $data, 
        $errorLvl = self::ERROR_LEVEL_INFO, 
        $additionalMessage = ''
    )
    {
        if(is_object($data)){
            $vars = array();
            $properties = get_class_vars(get_class($data));
            foreach ($properties as $name => $defaultVal) {
                $vars[$name] = $data->$name; 
            }   
            $data = $vars;   
        }
        
        $insertData = array(
            'date' => date('Y-m-d H:i:s'),
            'where' => $where,
            'errorLevel' => $errorLvl,
            'data' => $data,
            'additionalMessage' => $additionalMessage,
        );
        
        $logsCollection = $this->_db->selectCollection(
            Account_Lib_Query_LogsInformation::LOGS_COLLECTION
        );
        
        $logsCollection->insert($insertData);        
    }
}
