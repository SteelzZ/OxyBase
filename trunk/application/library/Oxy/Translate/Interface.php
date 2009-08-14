<?php
/**
* Oxy_Translate_Interface describes interface that has to be implemented by
* all classes that manages translate.
*
* @category Oxy
* @package Oxy_Translate
* @author Viktoras Puzas
* @version 1.0
**/
interface Oxy_Translate_Interface
{
  public function translateString($str_string_to_translate, $int_language = false);
  public function translateArray($arr_array_to_translate, $bl_translate_keys = true, $int_language = false);
  public function translateText($str_text_to_translate, $int_language = false);

}