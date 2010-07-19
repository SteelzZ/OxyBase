<?php
/**
 * Oxy collection
 *
 * @category Oxy
 * @package Oxy
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Collection implements Countable, IteratorAggregate, ArrayAccess
{
    /**
     * Collection type
     *
     * @var String
     */
    protected $_strValueType;

    /**
     * Collection elements
     *
     * @var Array
     */
    protected $_arrCollection = array();

    /**
     * Construct a new typed collection
     *
     * @param String valueType collection value type
     */
    public function __construct($strValueType)
    {
        $this->_strValueType = $strValueType;
    }

    /**
     * Add a value into the collection
     *
     * @param Mixed $mixValue
     * @param Mixed $mixKey
     *
     * @throws InvalidArgumentException when wrong type
     */
    public function add($mixValue, $mixKey = null)
    {
        if (!$this->isValidType($mixValue))
        {
            throw new InvalidArgumentException('Trying to add a value of wrong type');
        }

        if(!is_null($mixKey))
        {
            $this->_arrCollection[$mixKey] = $mixValue;
        }
        else
        {
            $this->_arrCollection[] = $mixValue;
        }

    }

    /**
     * Remove a value from the collection
     *
     * @param Mixed $mixKey index to remove
     *
     * @throws OutOfRangeException if index is out of range
     */
    public function remove($mixKey)
    {
        if (isset($this->_arrCollection[$mixKey]))
        {
            throw new OutOfRangeException('Index out of range');
        }

        unset($this->_arrCollection[$mixKey]);
    }

    /**
     * Return value at index
     *
     * @param Mixed $mixKey
     *
     * @return Mixed
     * @throws OutOfRangeException
     */
    public function get($mixKey)
    {
        if (isset($this->_arrCollection[$mixKey]))
        {
            throw new OutOfRangeException('Index out of range');
        }

        return $this->_arrCollection[$mixKey];
    }

    /**
     * Determine if index exists
     *
     * @param Mixed $mixKey
     *
     * @return boolean
     */
    public function exists($mixKey)
    {
        if (isset($this->_arrCollection[$mixKey]))
        {
            return true;
        }

        return false;
    }

    /**
     * Return count of items in collection
     * Implements countable
     *
     * @return Integer
     */
    public function count()
    {
        return count($this->_arrCollection);
    }

    /**
     * Determine if this value can be added to this collection
     *
     * @param Mixed $value
     * @return boolean
     */
    public function isValidType($mixValue)
    {
        if (is_object($mixValue))
        {
            if($mixValue instanceof $this->_strValueType)
            {
                return true;
            }
        }

        $strBaseType = gettype($mixValue);
        if ($this->_strValueType == $strBaseType)
        {
            return true;
        }

        return false;
    }

    /**
     * Return an iterator
     * Implements IteratorAggregate
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_arrCollection);
    }

    /**
     * Set offset to value
     * Implements ArrayAccess
     *
     * @see set
     * @param Mixed $mixKey
     * @param Mixed $mixValue
     */
    public function offsetSet($mixKey, $mixValue)
    {
        $this->add($mixValue, $mixKey);
    }

    /**
     * Unset offset
     * Implements ArrayAccess
     *
     * @see remove
     * @param Mixed $mixKey
     */
    public function offsetUnset($mixKey)
    {
        $this->remove($mixKey);
    }

    /**
     * get an offset's value
     * Implements ArrayAccess
     *
     * @see get
     * @param Mixed $mixKey
     *
     * @return Mixed
     */
    public function offsetGet($mixKey)
    {
        return $this->get($mixKey);
    }

    /**
     * Determine if offset exists
     * Implements ArrayAccess
     *
     * @see exists
     * @param Mixed $mixKey
     *
     * @return boolean
     */
    public function offsetExists($mixKey)
    {
        return $this->exists($mixKey);
    }
}
?>