<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/5
 * Time: 0:29
 * Doc: api controller demo
 */
namespace  web\controller;

use OursPHP\Core\Mvc\Controller\Controller;

class AController  extends Controller
{
    public function __construct($request, &$response) {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct($request, $response);
        parent::isApi();
    }
    public function GET_index($request,$response) {

        $response->title="这是标题";
        $response->body="這個是API模式 以GET方式 请求index控制器GET_index方法";
        $this->renderSmarty('index');
    }
    public function POST_index($request,$response) {

        $response->title="这是标题";
        $response->body="這個是API模式 以POST方式 请求index控制器POST_index方法";
        $this->renderSmarty('index');
    }
}