<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/5
 * Time: 0:29
 * Doc: api controller demo
 */
namespace  manage\controller;

use OursPHP\Core\Mvc\Controller\Controller;
use OursPHP\Core\Lib\Cookie\CookieManage;
use Symfony\Component\HttpFoundation\Cookie;

class ApiController  extends Controller
{
    public function __construct($request, &$response) {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct($request, $response);
        parent::isApi();
    }
    public function GET_index($request,$response) {

        $response->title="这是标题";
        $response->body="這個是API模式 以GET方式 请求{$this->_controller}控制器{$this->_action}方法";
        $this->renderSmarty('index');
    }
    public function GET_cookie($request,$response) {
        $cookie=new CookieManage('cc',0);
        dump($_COOKIE['cc_bb']);
        $data=$cookie->get('bb');
        dump($data);
        if($data)
        {
            echo $data;
        }else
            {
                $cookie->set('bb',time(),0);
                echo '生成';
            }
    }
    public function POST_index($request,$response) {

        $response->title="这是标题";
        $response->body="這個是API模式 以POST方式 请求{$this->_controller}控制器{$this->_action}方法";
        $this->renderSmarty('index');
    }
}