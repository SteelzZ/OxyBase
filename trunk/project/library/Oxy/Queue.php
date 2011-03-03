<?php
/**
 * Queue
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_Queue implements Oxy_Queue_QueueInterface
{
    /**
     * @var string
     */
    const OPTION_KEY_AUTO_COMMIT = 'auto-commit';
    
    /**
     * @var Oxy_Queue_Adapter_AdapterInterface
     */
    protected $_queueAdapter;
    
    /**
     * @var array
     */
    protected $_options;
 
    /**
     * @return Oxy_Queue_Adapter_AdapterInterface
     */
    public function getQueueAdapter()
    {
        return $this->_queueAdapter;
    }

	/**
     * @param Oxy_Queue_Adapter_AdapterInterface $queueAdapter
     * @param array $options
     */
    public function __construct(Oxy_Queue_Adapter_AdapterInterface $queueAdapter, array $options = array())
    {
        $this->_queueAdapter = $queueAdapter;        
        $this->_options = $options;
    }
    
    /**
     * @param array $options
     * 
     * @return mixed
     */
    private function checkAndGetOption($key)
    {
        if(isset($this->_options[$key])){
            return $this->_options[$key];
        } else {
            return false;
        }     
    }
    
    /**
     * @param Oxy_Queue_Message_MessageInterface $message
     */
    public function add(Oxy_Queue_Message_MessageInterface $message)
    {
        $this->_queueAdapter->add($message);
        if($this->checkAndGetOption(self::OPTION_KEY_AUTO_COMMIT)){
            $this->commit();
        }
    }
    
    /**
     * Commit messages
     */
    public function commit()
    {
        $this->_queueAdapter->commit();
    }

    /**
     * @return Oxy_Queue_Message_MessageInterface
     */
    public function get()
    {
        return $this->_queueAdapter->get();        
    }

    /**
     * @return void
     */
    public function purge()
    {
        $this->_queueAdapter->purge();
    }

    /**
     * @param Oxy_Queue_Message_MessageInterface $message
     */
    public function remove(Oxy_Queue_Message_MessageInterface $message)
    {
        $this->_queueAdapter->remove($message); 
    }

    /**
     * @return void
     */
    public function rollback()
    {
        $this->_queueAdapter->rollback();
    } 
}