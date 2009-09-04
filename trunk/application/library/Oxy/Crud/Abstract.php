<?php
/**
 * This component uses resources component to create resource
 * and then by using refelction adds required methods etc
 *
 * @author Administrator
 *
 */
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