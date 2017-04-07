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
use OursPHP\Core\Lib\Cache\Redis;
use OursPHP\Core\Lib\Cache\Memcached;
use App\Dao\System_Manager;

class ApiController  extends Controller
{
    public function __construct($request, &$response) {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct($request, $response);
        parent::isApi();
    }
    public  function GET_index($request,$response)
    {
        $rel=['a'=>1];
        $this->renderJson($rel);
    }
}