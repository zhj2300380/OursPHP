<?php
/**
* date：2017年3月2日
* author：zhaojin
* encoding：UTF-8
* file：test.php
* doc：
* 
**/
namespace App\Dao;


/**
 * 渠道商备案表
 * @author ZhaoJin
 *
 */
class Test extends MainBaseDao
{
	public function getTableName(){
		return 'test';
	}
	public function getPKey(){
		return 'id';
	}
}