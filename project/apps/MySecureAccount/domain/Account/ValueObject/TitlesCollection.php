<?php
class MySecureAccount_Domain_Account_ValueObject_TitlesCollection extends Oxy_Collection
{
    public function __construct(array $items = array())
    {
        parent::__construct(
            'MySecureAccount_Domain_Account_ValueObject_Title',
            $items
        );
    }
}