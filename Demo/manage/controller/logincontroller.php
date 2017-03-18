<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/8
 * Time: 1:37
 * Doc:
 */
namespace  manage\controller;

use App\Service\ManagerSvc;
use OursPHP\Core\Mvc\Controller\Controller;
use App\Dao\Test;
use OursPHP\Core\Lib\Cache\Memcached;
use OursPHP\Core\Lib\Cache\Redis;
use OursPHP\Core\Lib\Cookie\CookieManage;
use OursPHP\Core\Lib\Captcha\CaptchaManage;
use OursPHP\Core\Lib\Http\HttpClient;

class LoginController  extends Controller
{
    public function __construct($request, &$response) {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct($request, $response);
    }
    public function index($request,$response) {

        $msg='';
        if($this->isPost())
        {
            $vars=$request->post('vars');
            $rel= CaptchaManage::getInstance()->doCheck($vars['code']);
            if($rel)
            {
                $svc=new ManagerSvc();
                $user=$svc->userLogin($vars['username'],$vars['password']);
                if($user)
                {
                    $this->redirect("/index");
                }
                $msg='用户名密码错误';
            }
            if(empty($msg))
            {
                $msg="验证码错误";
            }
        }
        $response->msg=$msg;
        $response->title="管理员登录";
        $this->renderSmarty();
    }
    public function Captcha($request,$response)
    {
        CaptchaManage::getInstance(300,4,120,35)->doImg();
    }
    public function loginOut($request,$response)
    {
        $svc=new ManagerSvc();
        $svc->userLoginOut();
        $this->redirect('/index');
    }
}