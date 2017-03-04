<?php
/**
* date：2017年3月2日
* author：zhaojin
* encoding：UTF-8
* file：mainbasedao.php
* doc：
* 
**/
namespace App\Dao;

use OursPHP\Core\Lib\Db\mysql\BaseDao;


abstract class MainBaseDao extends BaseDao {

	protected function getMdbCfgName() {
		return "web1";
	}

	protected  function getSdbCfgName() {
		return "web1";
	}
}