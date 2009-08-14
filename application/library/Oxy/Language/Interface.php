<?php
/**
* Oxy system language interface
*
* @category Oxy
* @package Oxy_Language
* @author Tomas Bartkus
**/
interface Oxy_Language_Interface
{
	public function getId();

	public function getCode();

	public function getIso3();

	public function getTitle();

	public function isDefault();

	public function isVisible();
}
?>