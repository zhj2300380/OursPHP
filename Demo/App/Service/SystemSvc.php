<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/14
 * Time: 1:06
 * Doc:
 */
namespace App\Service;

use App\Dao\System_Function;
use App\Dao\System_Role;
use App\Dao\System_Manager;
use OursPHP\Core\Lib\AppendTrait\BaseService;

class SystemSvc extends BaseService
{


    public function getManagers($status=null)
    {
        $dao=new System_Manager();
        $where='';$query=[];
        if($status!==null)
        {
            $query['status']=$status;
            $where="status=:status";
        }
        return $dao->findAll($query,$where,0,[],'','','id asc');
    }
    public function getManager($id)
    {
        if($id)
        {
            $dao=new System_Manager();
            return $dao->findByPkey($id);
        }
        return false;
    }
    public function addManager($item)
    {
        if(!empty($item))
        {
            $dao=new System_Manager();
            return $dao->add($item);
        }
        return false;
    }
    public function editManager($id,$vars)
    {
        if(!empty($vars))
        {
            $dao=new System_Manager();
            return $dao->edit($id,$vars);
        }
        return false;
    }


    /**
     * 获取全部角色
     * @param null $status
     * @return mixed
     */
    public function getRoles($status=null)
    {
        $dao=new System_Role();
        $where='';$query=[];
        if($status!==null)
        {
            $query['status']=$status;
            $where="status=:status";
        }
        return $dao->findAll($query,$where,0,[],'','','id asc');
    }
    public function getRole($id)
    {
        if($id)
        {
            $dao=new System_Role();
            return $dao->findByPkey($id);
        }
        return false;
    }
    public function addRole($item)
    {
        if(!empty($item))
        {
            $dao=new System_Role();
            return $dao->add($item);
        }
        return false;
    }
    public function editRole($id,$vars)
    {
        if(!empty($vars))
        {
            $dao=new System_Role();
            return $dao->edit($id,$vars);
        }
        return false;
    }


    /**
     * 获取子功能列表
     * @param $pid
     * @return mixed
     */
    public function getFunctions($pid=0,$status=null)
    {
        $dao=new System_Function();
        $query['pid']=$pid;
        $where="pid=:pid";
        if($status!==null)
        {
            $query['status']=$status;
            $where=$where." and status=:status";
        }
        $field=['id','name','pid','icon','type','uri','description','ismenu','status'];

        return $dao->findAll($query,$where,0,$field,'','','id asc');
    }
    public function getFunction($id)
    {
        if($id)
        {
            $dao=new System_Function();
            return $dao->findByPkey($id);
        }
        return false;
    }

    /**
     * 添加系统功能
     * @param $item
     * @return bool|mixed
     */
    public function addFunction($item)
    {
        if(!empty($item))
        {
            $dao=new System_Function();
            return $dao->add($item);
        }
        return false;
    }
    public function editFunction($id,$vars)
    {
        if(!empty($vars))
        {
            $dao=new System_Function();
            return $dao->edit($id,$vars);
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getFunctionMap()
    {
        $functions=self::getFunctions(0,1);
        if(!empty($functions))
        {
            foreach ($functions as &$fun)
            {
                $fun['sub']=self::getFunctions($fun['id'],1);
            }
        }
        return $functions;
    }
}