<?php
/**
* Oxy system currency interface
*
* @category Oxy
* @package Oxy_Currency
* @author Tomas Bartkus
* @version 1.0
**/
interface Oxy_Currency_Interface
{
	public function getId();
	public function getCode();
	public function getTitle();
	public function getRate();
	public function isBase();
	public function isActive();
	public function isDefault();
}
?>