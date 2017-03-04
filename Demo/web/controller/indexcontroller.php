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


class IndexController  extends Controller
{
	public function __construct($request, &$response) {
		header("Content-type: text/html; charset=utf-8");
		//$this->_app_type='api';
		parent::__construct($request, $response);
	}
	public function index($request,$response) {
	    $mem=Memcached::getInstance();
        $time=$mem->get("key");

	    if(!$time)
	    {
            $mem->set("key",time(),200);
        }

        $redis=Redis::getInstance();
        $time1=$redis->get("key");

        if(!$time1)
        {
            $redis->set("key",'redis'.time(),200);
        }

	    dump($time,$time1);
		$dao=new Test();
		$rows=$dao->findAll();
		$response->title="这是标题";
		$response->rows=$rows;
		$response->body="这是普通模式";
		$this->renderSmarty();	
	}
	public function GET_index($request,$response) {
	
		$response->title="这是标题";
		$response->body="這個是API模式";
		$this->renderSmarty('index');
	}
}