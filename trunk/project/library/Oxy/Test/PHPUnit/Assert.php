<?php
abstract class Oxy_Test_PHPUnit_Assert extends PHPUnit_Framework_Assert
{
	/**
     * Asserts that a variable and an attribute of an object have the same type
     *
     * @param  mixed  $expected
     * @param  string $actualAttributeName
     * @param  object $actualClassOrObject
     * @param  string $message
     */
    public static function assertAttributeSameType($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        parent::assertType(
          $expected,
          parent::readAttribute($actualClassOrObject, $actualAttributeName),
          $message
        );
    }
}