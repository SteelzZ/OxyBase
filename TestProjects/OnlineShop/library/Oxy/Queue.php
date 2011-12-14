<?php
/**
 * Queue
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
namespace Oxy;
use Oxy\Queue\QueueInterface;
use Oxy\Queue\Adapter\AdapterInterface;
use Oxy\Queue\Message\MessageInterface;

class Queue implements QueueInterface
{
    /**
     * @var string
     */
    const OPTION_KEY_AUTO_COMMIT = 'auto-commit';
    
    /**
     * @var AdapterInterface
     */
    protected $_queueAdapter;
    
    /**
     * @var array
     */
    protected $_options;
 
    /**
     * @return AdapterInterface
     */
    public function getQueueAdapter()
    {
        return $this->_queueAdapter;
    }

	/**
     * @param AdapterInterface $queueAdapter
     * @param array $options
     */
    public function __construct(AdapterInterface $queueAdapter, array $options = array())
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
     * @param MessageInterface $message
     */
    public function add(MessageInterface $message)
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
     * @return MessageInterface
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
     * @param MessageInterface $message
     */
    public function remove(MessageInterface $message)
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