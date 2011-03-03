<?php
abstract class Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * Convert object to array
     * 
     * @return array
     */
    public function toArray()
    {
        $properties = get_class_vars(get_class($this));
        $vars = array();
        foreach ($properties as $name => $defaultVal) {
            $vars[str_replace('_', '', $name)] = $this->$name; 
        }  

        return $vars;
    }
    
    /**
     * @param string $className
     * @param array $params
     * 
     * @return Oxy_Domain_ValueObject_ArrayableAbstract
     */
    public static function createFromArray($className, $params)
    {
        $reflectedClass = new ReflectionClass($className);
        $classInstance = $reflectedClass->newInstanceArgs($params);
        
        return $classInstance;         
    }
}