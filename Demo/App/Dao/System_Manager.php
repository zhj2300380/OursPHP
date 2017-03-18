<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/13
 * Time: 23:02
 * Doc:
 */
namespace App\Dao;

class System_Manager extends Base
{
    public function getTableName(){
        return 'system_manager';
    }
    public function getPKey(){
        return 'id';
    }
    public function lock($id)
    {
        $fun=self::findByPkey($id);
        if($fun)
        {
            $vars['status']=$fun['status']==1?0:1;
            //dump($vars['status'],$fun['status']);
            return self::edit($id,$vars);
        }
        return false;
    }
}