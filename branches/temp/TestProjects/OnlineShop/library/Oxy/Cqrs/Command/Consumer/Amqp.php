<?php
/**
 * Commands consumer
 *
 * @category Oxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Cqrs\Command\Consumer;
use Oxy\Queue\Consumer\Amqp;
use Oxy\Command\Handler\Builder\BuilderInterface;
use Oxy\Queue\Adapter\Rabbitmq;
use Oxy\Cqrs\Command\Handler\Builder\BuilderInterface;
use Oxy\Queue\Consumer\ConsumerInterface;
use Oxy\Queue\Message\MessageInterface;
use Oxy\Queue\Message;
use Oxy\EventStore\Storage\CouldNotSaveEventsException;
use Oxy\EventStore\Storage\CouldNotSaveSnapShotException;
use Oxy\Cqrs\Command\Consumer\Exception;
use Oxy\Domain\Exception as DomainException;
use Oxy\EventStore\Event\WrongStateException;
use Oxy\EventStore\Storage\ConcurrencyException;
use Oxy\EventStore\Storage\EntityAlreadyExistsException;

class Amqp extends Amqp
{
    /**
     * @var BuilderInterface
     */
    protected $_commandHandlerBuilder;
            
    /**
     * Yes this is wrong:
     * Oxy_Queue_Adapter_Rabbitmq
     * 
     * We should have interface here
     * But for now ...
     * 
     * @param Rabbitmq $queue
     * @param BuilderInterface $commandHandlerBuilder
     * @param array $options
     */
    public function __construct(
        Rabbitmq $queueAdapter, 
        BuilderInterface $commandHandlerBuilder,
        array $options = array()
    )
    {
        parent::__construct($queueAdapter, $options);
        $this->_commandHandlerBuilder = $commandHandlerBuilder;                       
    }
    
    /**
     * @see ConsumerInterface::consume()
     */
    public function consume()
    {
        while($message = $this->_queue->get()){
            $this->processMessage($message, false);
        }
        //$this->_queue->getChannel()->basic_recover();
        
        if (isset($this->_options['become-listener']) && (boolean)$this->_options['become-listener'] === true) {
            $this->listen();
        }        
    }
       
    /**
     * @see ConsumerInterface::listen()
     */
    public function listen()
    {
        $consumerName = isset($this->_options['consumer-name']) ? $this->_options['consumer-name'] : 'oxy-consumer';
        $this->_queue
             ->getChannel()
             ->basic_qos(0,1,0); 
             
        $this->_queue
             ->getChannel()
             ->basic_consume(
                 $this->_queue->getQueueId(), 
                 $consumerName, 
                 false, 
                 false, 
                 false, 
                 false, 
                 array($this, 'processMessage')
             );
             
        while (count($this->_queue->getChannel()->callbacks)) {
            $this->_queue->getChannel()->wait();
        }
    }

    /**
     * Process a message
     * @param mixed $message
     * @param boolean $factory
     */
    public function processMessage($message, $factory = true)
    {      
        try{  
            if($factory){
                $message = Message::factory($message);
            }
            if ($message instanceof MessageInterface){
                $command = $message->getContent();
                if($command instanceof \__PHP_Incomplete_Class){
                    $this->_queue->remove($message);
                } else {
                    //var_dump($command);
                    
                    //$this->_logger->write('Building handler for command ' . $command->getCommandname());
                    $commandHandler = $this->_commandHandlerBuilder->buildCommandHandlerForCommand($command);
                                        
                    //echo "\n Executing " . $command->getCommandName();
                    //$this->_logger->write('Executing command ' . $command->getCommandname());
                    //$s = microtime(true);
                    $commandHandler->execute($command);
                    //$e = microtime(true);
                    //$elapsed = $e - $s;
                    //echo "+{$message->getId()}+ in {$elapsed} sec";
                    
                    $this->_queue->remove($message);
                }
            } else if($message){
                $this->_queue->remove($message);
            }
              
            $this->_queue->commit();
        } catch (CouldNotSaveEventsException $e) {  
            //$this->_logger->write('Oxy_EventStore_Storage_CouldNotSaveEventsException - ' . $e->getTraceAsString());
            throw new Exception('Could not save events');           
        } catch (CouldNotSaveSnapShotException $e) {  
            //$this->_logger->write('Oxy_EventStore_Storage_CouldNotSaveSnapShotException - ' . $e->getTraceAsString());
            throw new Exception('Could not save snapshot');
        } catch (DomainException $e) {  
            //$this->_logger->write('Oxy_Domain_Exception - ' . $e->getTraceAsString());
            $this->_queue->remove($message);
            $this->_queue->commit();
        } catch (WrongStateException $e) {           
            if($message->isRedelivered()){
                $this->_logger->write('Message redelivered - (removing) - ' . $e->getMessage() . ' ' . $e->getTraceAsString());
                $this->_queue->remove($message);
                $this->_queue->commit();
            } else {
                $this->_logger->write('Oxy_EventStore_Event_WrongStateException - ' . $e->getMessage() . ' ' . $e->getTraceAsString());
                $this->_queue->getChannel()->basic_reject($message->getId(), true);
                $this->_queue->rollback();
            }
        } catch (ConcurrencyException $e) {   
            //$this->_logger->write('Oxy_EventStore_Storage_ConcurrencyException - ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->_queue->getChannel()->basic_reject($message->getId(), true);
            $this->_queue->rollback();
        } catch (EntityAlreadyExistsException $e) {          
            //$this->_logger->write('Oxy_EventStore_Storage_EntityAlreadyExistsException - ' . $e->getMessage() . ' ' . $e->getTraceAsString());    
            $this->_queue->remove($message);
            $this->_queue->commit();
        }
    }
}