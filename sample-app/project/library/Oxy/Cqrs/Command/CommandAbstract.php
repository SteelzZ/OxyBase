<?php
/**
 * Base command class
 *
 * @category Oxy
 * @package Oxy_Cqrs
 * @subpackage Command
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Cqrs_Command_CommandAbstract implements Oxy_Cqrs_Command_CommandInterface
{
    /**
     * @var String
     */
    protected $_commandName;

    /**
     * @var Oxy_Guid
     */
    protected $_guid;

    /**
     * @var string
     */
    protected $_realIdentifier;

    /**
     * Initialize command
     *
     * @param string $commandName
     * @param Oxy_Guid $guid
     * @param string $realIdentifier
     *
     * @return void
     */
    public function __construct($commandName, Oxy_Guid $guid, $realIdentifier)
    {
        $this->_commandName = $commandName;
        $this->_guid = $guid;
        $this->_realIdentifier = $realIdentifier;
    }

    /**
     * Return command name
     *
     * @return string
     */
    public function getCommandName()
    {
        return $this->_commandName;
    }

    /**
     * Return GUID
     *
     * @return Oxy_Guid
     */
    public function getGuid()
    {
        return $this->_guid;
    }

    /**
     * Return real identifier
     *
     * @return Oxy_Guid
     */
    public function getRealIdentifier()
    {
        return $this->_realIdentifier;
    }
       
    /**
     * Create command
     * 
     * @param string $targetClass
     * @param array $params
     */
    public static function factory($targetClass, $params)
    {
        $reflectedClass = new ReflectionClass($targetClass);
        if ($reflectedClass->isSubclassOf(__CLASS__)){
            $guid = array_shift($params);
            if(!($guid instanceof Oxy_Guid)){
                $guid = new Oxy_Guid((string)$guid);
            }
            array_unshift($params, $guid);
            array_unshift($params, $targetClass);
            $commandInstance = $reflectedClass->newInstanceArgs($params);
            
            return $commandInstance;
        } else {
            throw new Oxy_Cqrs_Command_Exception("The command '$targetClass' doesn not exists.");
        }    
    }
}