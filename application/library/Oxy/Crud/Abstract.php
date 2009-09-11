<?php
/**
* This component uses resources component to create resource
* and then by using refelction adds required methods etc
*
* @category Oxy
* @package Oxy_Crud
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
abstract class Oxy_Crud_Abstract
{
	function __construct()
	{
	}

	function __destruct()
	{
	}

	public function setAdapter()
	{

	}

	public function getAdapter()
	{

	}

	public function generate()
	{
		// agregate
	}

	public function generateModels()
	{
		// adapter -> generate models
	}

	public function generateContrrollers()
	{
		// adapter -> generate controllers
	}

	public function generateViews()
	{
		// adapter -> generate views
	}
}
?>