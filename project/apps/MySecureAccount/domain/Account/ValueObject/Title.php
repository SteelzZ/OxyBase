<?php
class MySecureAccount_Domain_Account_ValueObject_Title
{
    /**
     * @var string
     */
    private $_title;
    
    /**
     * @param string $state
     */
    public function __construct($title)
    {
        $this->_title = $title;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->_title;
    }
}