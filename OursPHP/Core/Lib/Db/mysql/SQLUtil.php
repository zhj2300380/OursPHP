<?php
namespace OursPHP\Core\Lib\Db\mysql;

class SqlUtil {

	/**
	 * 从数组组合成一个符合sql的in条件
	 * @param array $vars
	 * @return string
	 */
	public static function buildIn(array $vars) {
		return " ('".implode("', '", $vars)."') ";
	}
	
	/**
	* 创建像这样的查询: "IN('a','b')";
	*
	* @access   public
	* @param    mix      $item_list      列表数组或字符串,如果为字符串时,字符串只接受数字串
	* @param    string   $field_name     字段名称
	*
	* @return   void
	*/
	public static function db_create_in($item_list, $field_name = '')
	{
		if (empty($item_list))
		{
			return $field_name . " IN ('') ";
		}
		else
		{
			if (!is_array($item_list))
			{
				$item_list = explode(',', $item_list);
				foreach ($item_list as $k=>$v)
				{
					$item_list[$k] = intval($v);
				}
			}
	
			$item_list = array_unique($item_list);
			$item_list_tmp = '';
            foreach ($item_list AS $item)
            {
                if ($item !== '')
                {
                    $itemval=is_numeric($item)?$item:"'$item'";
                    $_tmparr[]=$itemval;
                }
            }
            $item_list_tmp=implode(',',$_tmparr);
			if (empty($item_list_tmp))
			{
				return $field_name . " IN ('') ";
			}
			else
			{
				return $field_name . ' IN (' . $item_list_tmp . ') ';
			}
		}
	}	
	
}
?>