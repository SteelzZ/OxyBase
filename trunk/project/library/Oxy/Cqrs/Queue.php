<?php
/**
 * Oxy Queue
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_Cqrs_Queue extends Oxy_Queue
{
    /**
     * @param Oxy_Cqrs_Command_CommandInterface $command
     */
    public function addCommand(Oxy_Cqrs_Command_CommandInterface $command)
    {
        $message = new Oxy_Queue_Message(
            (string)new Msc_Guid(), 
            Oxy_Utils_String::serialize($command), 
            Oxy_Queue_Message::TYPE_PLAIN_TEXT,
            Oxy_Queue_Message::DELIVERY_MODE_PERSISTANCE
        );
        
        return $this->add($message);
    }
}