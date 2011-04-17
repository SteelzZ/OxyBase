<?php
class Account_Domain_Account_ValueObject_DeviceInformation
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{        
    /**
     * @var string
     */
    protected $_name;
    
    /**
     * @var string
     */
    protected $_title;
    
    /**
     * @var Account_Domain_Account_ValueObject_DeviceType
     */
    protected $_type;
    
    /**
     * @var Account_Domain_Account_ValueObject_OperatingSystem
     */
    protected $_operatingSystem;
            
    /**
     * @var Account_Domain_Account_ValueObject_Properties
     */
    protected $_settings;
    
    /**
     * @param string $name
     * @param string $title
     * @param Account_Domain_Account_ValueObject_DeviceType $type
     * @param Account_Domain_Account_ValueObject_OperatingSystem $operatingSystem
     * @param Account_Domain_Account_ValueObject_Properties $settings
     */
    public function __construct(
        $name, 
        $title, 
        Account_Domain_Account_ValueObject_DeviceType $type,
        Account_Domain_Account_ValueObject_OperatingSystem $operatingSystem,
        Account_Domain_Account_ValueObject_Properties $settings
    )
    {
        $this->_name = $name;                 
        $this->_title = $title;                 
        $this->_type = $type;                 
        $this->_operatingSystem = $operatingSystem;                
        $this->_settings = $settings;                 
    }
    
	/**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
	/**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

	/**
     * @return Account_Domain_Account_ValueObject_DeviceType
     */
    public function getType()
    {
        return $this->_type;
    }

	/**
     * @return Account_Domain_Account_ValueObject_OperatingSystem
     */
    public function getOperatingSystem()
    {
        return $this->_operatingSystem;
    }

	/**
     * @return Account_Domain_Account_ValueObject_Properties
     */
    public function getSettings()
    {
        return $this->_settings;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_name;
    }
    
	/**
     * @param string $className
     * @param array $params
     * 
     * @return Oxy_Domain_ValueObject_ArrayableAbstract
     */
    public static function createFromArray($className, $params)
    {
        try{
            $reflectedClass = new ReflectionClass($className);
            
            $constructedParams = array(
                $params['deviceName'],
                $params['deviceTitle'],
                new Account_Domain_Account_ValueObject_DeviceType(
                    $params['deviceType']
                ),
                new Account_Domain_Account_ValueObject_OperatingSystem(
                    $params['operatingSystem'],
                    $params['operatingSystemType']
                ),
                Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
                    'Account_Domain_Account_ValueObject_Properties', 
                    array($params['settings'])
                ),
            );   
            $classInstance = $reflectedClass->newInstanceArgs($constructedParams);
            
            return $classInstance;     
        } catch (Exception $ex){
            throw new Oxy_Domain_Exception(
                sprintf(
                    'Could not create [%s] class instance! Exact message was [%s]',
                    $className,
                    $ex->getMessage()
                )
            );
        }    
    }
    
    /**
     * Convert object to array
     * 
     * @return array
     */
    public function toArray()
    {
        return array(
            'name' => $this->_name,
            'title' => $this->_title,
            'type' => (string)$this->_type,
            'operatingSystem' => $this->_operatingSystem->toArray(),
            'settings' => $this->_settings->getProperties()
        );
    }
}