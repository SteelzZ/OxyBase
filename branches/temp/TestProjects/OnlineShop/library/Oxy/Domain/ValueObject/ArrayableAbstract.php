<?php
namespace Oxy\Domain\ValueObject;
use Oxy\Domain\Exception;

abstract class ArrayableAbstract
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
        try{
            $reflectedClass = new \ReflectionClass($className);
            $classInstance = $reflectedClass->newInstanceArgs($params);
            
            return $classInstance;  
        } catch (\Exception $ex){
            throw new Exception(
                sprintf(
                    'Could not create [%s] class instance! Exact message was [%s]',
                    $className,
                    $ex->getMessage()
                )
            );
        }
    }
}