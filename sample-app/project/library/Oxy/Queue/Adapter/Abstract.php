<?php
/**
 * Oxy queue adapter
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Queue_Adapter_Abstract implements Oxy_Queue_Adapter_AdapterInterface
{
    /**
     * Queue id
     * 
     * @var string
     */
    protected $_queueId;
    
    /**
     * Options
     * 
     * @var array
     */
    protected $_options;

    /**
     * @return string
     */
    public function getQueueId() 
    {
        return $this->_queueId;
    }
    
    /**
     * @param string $options
     */
    public function __construct($options = null)
    {
        if (!is_null($options)) {
            if(is_string($options)){
                $this->_options = parse_url($options);
                if (isset($this->_options['query']) && is_string($this->_options['query'])) {
                    $additionalOptions = explode('|', $this->_options['query']);
    
                    if(is_array($additionalOptions) && count($additionalOptions) > 0){
                        foreach ($additionalOptions as $additionalOption){
                            $additionalParams = explode('=', $additionalOption);
                            if(is_array($additionalParams) && (isset($additionalParams[0]) && isset($additionalParams[1]))){
                                $this->_options[$additionalParams[0]] = $additionalParams[1];
                            }
                        }
                        unset($this->_options['query']);
                    }
                }
                
                if (isset($this->_options['queue-id']) && is_string($this->_options['queue-id'])) {
                    $this->_queueId = $this->_options['queue-id'];
                }
                
                if (isset($this->_options['delivery-mode']) && is_int($this->_options['delivery-mode'])) {
                    $this->_queueId = $this->_options['queue-id'];
                }
            } 
        }
    }
}