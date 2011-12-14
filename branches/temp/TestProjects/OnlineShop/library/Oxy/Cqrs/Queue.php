<?php
/**
 * Oxy Queue
 *
 * @category Oxy
 * @package Oxy_Queue
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Cqrs;
use Oxy\Queue;
use Oxy\Cqrs\Command\CommandInterface;
use Oxy\Queue\Message;
use Oxy\Guid;
use Oxy\Utils\String;

class Queue extends Queue
{
    /**
     * @param CommandInterface $command
     */
    public function addCommand(CommandInterface $command)
    {
        $message = new Message(
            (string)new Guid(), 
            String::serialize($command), 
            Message::TYPE_PLAIN_TEXT,
            Message::DELIVERY_MODE_PERSISTANCE
        );
        
        return $this->add($message);
    }
}