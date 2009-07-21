<?php
/**
 * Helper tree class
 *
 * @category Oxy
 * @package Oxy_Tree
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Tree_Helper
{
	/**
	 * Generate tree from given array
	 *
	 * @param Array $arr_tree
	 * @param Integer $int_root
	 *
	 * @return Array
	 */
	public static function generateTree($arr_tree, $int_root, $str_parent_key = 'parent_id')
	{
		$arr_lookup = array();
		foreach ($arr_tree as $arr_item)
		{
			$arr_item['children'] = array();
			$arr_lookup[$arr_item['id']] = $arr_item;
		}
		$arr_tree = array();
		foreach ($arr_lookup as $int_id => $mix_value)
		{
			$arr_item = &$arr_lookup[$int_id];
			if ($arr_item[$str_parent_key] == $int_root)
			{
				$arr_tree[$int_id] = &$arr_item;
			}
			else if (isset($arr_lookup[$arr_item[$str_parent_key]]))
			{
				$arr_lookup[$arr_item[$str_parent_key]]['children'][$int_id] = &$arr_item;
			}
			else
			{
				$arr_tree['_orphans_'][$int_id] = &$arr_item;
			}
		}
		return $arr_tree;
	}
}
?>