<?php
class MySecureAccount_Domain_Account_ValueObject_DeviceInformation
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
     * @var MySecureAccount_Domain_Account_ValueObject_DeviceType
     */
    protected $_type;
    
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_OperatingSystem
     */
    protected $_operatingSystem;
            
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_Properties
     */
    protected $_settings;
    
    /**
     * @param string $name
     * @param string $title
     * @param MySecureAccount_Domain_Account_ValueObject_DeviceType $type
     * @param MySecureAccount_Domain_Account_ValueObject_OperatingSystem $operatingSystem
     * @param MySecureAccount_Domain_Account_ValueObject_Properties $settings
     */
    public function __construct(
        $name, 
        $title, 
        MySecureAccount_Domain_Account_ValueObject_DeviceType $type,
        MySecureAccount_Domain_Account_ValueObject_OperatingSystem $operatingSystem,
        MySecureAccount_Domain_Account_ValueObject_Properties $settings
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
     * @return MySecureAccount_Domain_Account_ValueObject_DeviceType
     */
    public function getType()
    {
        return $this->_type;
    }

	/**
     * @return MySecureAccount_Domain_Account_ValueObject_OperatingSystem
     */
    public function getOperatingSystem()
    {
        return $this->_operatingSystem;
    }

	/**
     * @return MySecureAccount_Domain_Account_ValueObject_Properties
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
                new MySecureAccount_Domain_Account_ValueObject_DeviceType(
                    $params['deviceType']
                ),
                new MySecureAccount_Domain_Account_ValueObject_OperatingSystem(
                    $params['operatingSystem'],
                    $params['operatingSystemType']
                ),
                Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
                    'MySecureAccount_Domain_Account_ValueObject_Properties', 
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