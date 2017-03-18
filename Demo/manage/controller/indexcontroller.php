<?php
/**
* date：2017年3月2日
* author：zhaojin
* encoding：UTF-8
* file：indexcontroller.php
* doc：
* 
**/
namespace  manage\controller;

use OursPHP\Core\Lib\Cookie\CookieManage;
use OursPHP\Core\Lib\Captcha\CaptchaManage;
use manage\controller\BaseController;

class IndexController  extends BaseController
{
	public function __construct($request, &$response) {
		header("Content-type: text/html; charset=utf-8");
		parent::__construct($request, $response);
		//$this->befor()
	}
	public function index($request,$response) {

        $response->title="Dashboard";
        $response->stitle="欢迎光临";
        $this->layoutSmarty();
	}
    /**
     * cookie 读取
     */
	public function getUserInfoByCookie()
    {
        $userinfo=CookieManage::getInstance()->get('userinfo');
        dump($userinfo);
    }

    /**
     * cookie 写入
     */
    public function setUserInfoByCookie()
    {
        $userinfo=['uid'=>1,'name'=>'hello','sex'=>0];
        $rel=CookieManage::getInstance()->set('userinfo',$userinfo,120);
        dump($rel);
    }
    /**
     * 生成验证码 验证码有效期5分钟
     * @param $request
     * @param $response
     */
	public function CreateCode($request,$response)
    {
        CaptchaManage::getInstance()->doImg();
    }

    /**
     * 检查验证码
     * @param $request
     * @param $response
     */
    public function CheckCode($request,$response)
    {
        $code=$request->code;
        $rel= CaptchaManage::getInstance()->doCheck($code);
        dump($rel);
    }
    public function test($request,$response) {

        $response->title="这是标题";
        $response->body="這個是API模式 以GET方式 请求{$this->_controller}控制器{$this->_action}方法";
        $this->renderSmarty();
    }
}