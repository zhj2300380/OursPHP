<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/8
 * Time: 2:14
 * Doc:
 */
namespace App\Service;

use App\Dao\System_Manager;
use OursPHP\Core\Lib\AppendTrait\BaseService;
use OursPHP\Core\Lib\Cookie\CookieManage;

class ManagerSvc extends BaseService
{
    public function userLoginOut()
    {
        $cookieManage=new CookieManage('manager',1800);
        $cookieManage->clear('info');
    }
    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function userLogin($username,$password)
    {
        $dao=new System_Manager();
        $query['username']=$username;
        $where=' username=:username and status=1';
        $user=$dao->findOne($query,$where);
        if($user && md5($password)===$user['password'])
        {
            $cookieManage=new CookieManage('manager',1800);
            $cookieManage->set('info',$user);
            return $user;
        }
        return false;
    }
}