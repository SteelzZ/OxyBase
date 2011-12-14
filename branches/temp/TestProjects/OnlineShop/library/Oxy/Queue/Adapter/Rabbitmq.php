<?php
/**
 * Oxy queue RabbitMQ adapter
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Queue\Adapter;
use Oxy\Queue\Adapter\AdapterAbstract;
use Oxy\Queue\Adapter\Exception as AdapterException;
use Oxy\Queue\Message\MessageInterface;

class Rabbitmq extends AdapterAbstract 
{
    /**
     * @var AMQPChannel
     */
    private $_channel;
    
    /**
     * @var AMQPConnection
     */
    private $_connection;
    
    /**
     * @return AMQPChannel
     */
    public function getChannel() 
    {
        return $this->_channel;
    }
    
    /**
     * @return AMQPConnection
     */
    public function getConnection() 
    {
        return $this->_connection;
    }
    
    /**.
     * @param string $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        try{
            $this->_connection = new AMQPConnection(
                $this->_options['host'], 
                $this->_options['port'], 
                $this->_options['user'], 
                $this->_options['pass'], 
                $this->_options['path']
            );
            
            $this->_channel = $this->_connection->channel();
            $this->_channel->queue_declare($this->_queueId, false, true);
            $this->_channel->exchange_declare($this->_queueId, 'direct', false, true, false);
            $this->_channel->queue_bind($this->_queueId, $this->_queueId);
            
            if(isset($this->_options['txt-mode']) && (boolean)$this->_options['txt-mode'] === true){
                $this->_channel->tx_select();
            }
            
        } catch (Exception $e){
            throw new AdapterException("Could not connecto to RabbitMQ broker");
        }
    }

    /**
     * @param MessageInterface $message
     */
    public function add(MessageInterface $message)
    {
        $message = new AMQPMessage(
            $message->getContent(), 
            array(
            	'content_type' => $message->getContentType(), 
            	'delivery_mode' => $message->getDeliveryMode()
            )
        );
        $this->_channel->basic_publish($message, $this->_queueId);
    }
    
    /**
     * Commit messages
     */
    public function commit()
    {
        $this->_channel->tx_commit();
    }
    
    /**
     * Rollback messages
     */
    public function rollback()
    {
        $this->_channel->tx_rollback();
    }

    /**
     * @return MessageInterface
     */
    public function get()
    {
        $message = $this->_channel->basic_get($this->_queueId);
        if ($message) {
            return Oxy_Queue_Message::factory($message);
        } else {
            return null;
        }
    }

    /**
     * Clean up
     */
    public function purge()
    {
        $this->_channel->queue_purge($this->_queueId);
    }

    /**
     * @param MessageInterface $message
     */
    public function remove(MessageInterface $message)
    {
        $this->_channel->basic_ack($message->getId());
    }
}