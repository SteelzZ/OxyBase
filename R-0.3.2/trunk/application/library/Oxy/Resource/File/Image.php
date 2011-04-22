<?php
/**
* Image file resources
*
* @category Oxy
* @package Oxy_Resource
* @author Tomas Bartkus
**/
class Oxy_Resource_File_Image extends Oxy_Resource_File
{
	/**
     * Apply template to file
     *
     * @return String
     */
    protected function applyTemplate()
    {
        $str_tpl_contents = file_get_contents($this->getTemplateName(), FILE_BINARY);
        file_put_contents($this->getResourceName(), $str_tpl_contents, FILE_BINARY);
    }
}