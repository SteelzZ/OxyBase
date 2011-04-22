<?php
/**
 * Commands consumer
 *
 * @category Oxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_Cqrs_Command_Consumer_Amqp 
    extends Oxy_Queue_Consumer_Amqp
{
    /**
     * @var Msc_Command_Handler_Builder_BuilderInterface
     */
    protected $_commandHandlerBuilder;
            
    /**
     * Yes this is wrong:
     * Oxy_Queue_Adapter_Rabbitmq
     * 
     * We should have interface here
     * But for now ...
     * 
     * @param Oxy_Queue_Adapter_Rabbitmq $queue
     * @param Msc_Command_Handler_Builder_BuilderInterface $commandHandlerBuilder
     * @param array $options
     */
    public function __construct(
        Oxy_Queue_Adapter_Rabbitmq $queueAdapter, 
        Oxy_Cqrs_Command_Handler_Builder_BuilderInterface $commandHandlerBuilder,
        array $options = array()
    )
    {
        parent::__construct($queueAdapter, $options);
        $this->_commandHandlerBuilder = $commandHandlerBuilder;                       
    }
    
    /**
     * @see Oxy_Queue_Consumer_ConsumerInterface::consume()
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
     * @see Oxy_Queue_Consumer_ConsumerInterface::listen()
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
                $message = Oxy_Queue_Message::factory($message);
            }
            if ($message instanceof Oxy_Queue_Message_MessageInterface){
                $command = $message->getContent();
                if($command instanceof __PHP_Incomplete_Class){
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
        } catch (Oxy_EventStore_Storage_CouldNotSaveEventsException $e) {  
            $this->_logger->write('Oxy_EventStore_Storage_CouldNotSaveEventsException - ' . $e->getTraceAsString());
            throw new Oxy_Cqrs_Command_Consumer_Exception('Could not save events');           
        } catch (Oxy_EventStore_Storage_CouldNotSaveSnapShotException $e) {  
            $this->_logger->write('Oxy_EventStore_Storage_CouldNotSaveSnapShotException - ' . $e->getTraceAsString());
            throw new Oxy_Cqrs_Command_Consumer_Exception('Could not save snapshot');
        } catch (Oxy_Domain_Exception $e) {  
            //echo "\n Removing" .$e->getMessage();
            
            $this->_logger->write('Oxy_Domain_Exception - ' . $e->getTraceAsString());
            $this->_queue->remove($message);
            $this->_queue->commit();
        } catch (Oxy_EventStore_Event_WrongStateException $e) {           
            //echo "\n " .$e->getMessage();
            //var_dump($message);
            if($message->isRedelivered()){
                $this->_logger->write('Message redelivered - (removing) - ' . $e->getMessage() . ' ' . $e->getTraceAsString());
                $this->_queue->remove($message);
                $this->_queue->commit();
            } else {
                $this->_logger->write('Oxy_EventStore_Event_WrongStateException - ' . $e->getMessage() . ' ' . $e->getTraceAsString());
                $this->_queue->getChannel()->basic_reject($message->getId(), true);
                $this->_queue->rollback();
            }
        } catch (Oxy_EventStore_Storage_ConcurrencyException $e) {           
            //echo "\n " .$e->getMessage();
            $this->_logger->write('Oxy_EventStore_Storage_ConcurrencyException - ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->_queue->getChannel()->basic_reject($message->getId(), true);
            $this->_queue->rollback();
        } catch (Oxy_EventStore_Storage_EntityAlreadyExistsException $e) {           
            //echo "\n Remove .." .$e->getMessage();        
            $this->_logger->write('Oxy_EventStore_Storage_EntityAlreadyExistsException - ' . $e->getMessage() . ' ' . $e->getTraceAsString());    
            $this->_queue->remove($message);
            $this->_queue->commit();
        }
    }
}