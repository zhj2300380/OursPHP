<?php
/**
* date：2017年3月2日
* author：zhaojin
* encoding：UTF-8
* file：indexcontroller.php
* doc：
* 
**/
namespace  web\controller;

use OursPHP\Core\Mvc\Controller\Controller;
use App\Dao\Test;
use OursPHP\Core\Lib\Cache\Memcached;
use OursPHP\Core\Lib\Cache\Redis;
use OursPHP\Core\Lib\Cookie\CookieManage;
use OursPHP\Core\Lib\Captcha\CaptchaManage;
use OursPHP\Core\Lib\Http\HttpClient;

class IndexController  extends Controller
{
	public function __construct($request, &$response) {
		header("Content-type: text/html; charset=utf-8");
		parent::__construct($request, $response);
		//$this->befor()
	}
	public function index($request,$response) {

	    //输入输出
	    $c=$request->setOpenFilter()->_c;//过滤输入
        $c=$request->setOpenFilter(false)->_c;//非过滤输入
        $response->title="这是标题";
        $response->body="这是普通模式";




        /*
        memcached && redis
        $mem=Memcached::getInstance();
        $data=$mem->get("key");

        if(!$data)
        {
            $data=time();
            $mem->set("key",$data,200);
        }

        $redis=Redis::getInstance();
        $data=$redis->get("key");

        if(!$data)
        {
            $data=time();
            $redis->set("key",$data,200);
        }

        dao


        $dao=new Test();
        $rows=$dao->findAll();//直连数据库
        $rows=$dao->findAll_with_cache('cache_time=300','cache_key=xxxxx');//通过缓存取数据
        $rows=$dao->findAll_with_cache('cache_time=300');//通过缓存取数据
        $rows=$dao->findAll_with_cache();//通过缓存取数据
        dump($rows);
         **/





        $this->renderSmarty();
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
}