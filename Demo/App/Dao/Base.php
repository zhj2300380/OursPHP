<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/13
 * Time: 23:00
 * Doc:
 */
namespace App\Dao;

use OursPHP\Core\Lib\Db\mysql\BaseDao;


abstract class Base extends BaseDao {

    protected function getMdbCfgName() {
        return "web1";
    }

    protected  function getSdbCfgName() {
        return "web1";
    }
}