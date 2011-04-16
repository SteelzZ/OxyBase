<?php
class MySecureAccount_Domain_Account_ValueObject_EmailAddress
{
    /**
     * @var string
     */
    private $_emailAddress;
    
    /**
     * @param string $emailAddress
     */
    public function __construct($emailAddress)
    {
        if(empty($emailAddress)){
            throw new Oxy_Domain_Exception(
                sprintf('%s VO does not allow empty string!', get_class($this))
            );
        }
        $this->_emailAddress = $emailAddress;        
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_emailAddress;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->_emailAddress;
    }
}