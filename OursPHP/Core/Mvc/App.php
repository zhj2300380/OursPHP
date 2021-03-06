<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 0:58
 * Doc: 简单的mvc实现
 */
namespace OursPHP\Core\Mvc;

use OursPHP\Core\Mvc\Http\Request;
use OursPHP\Core\Mvc\Http\Response;
use OursPHP\Core\Common\BizException;
use OursPHP\Init\ConfigManage;

class App {

    private $_controller = 'index';
    private $_action = 'index';
    private $_app_namespace = '';
    private $_request;

    public function __construct($postfix) {
        $this->_request = Route::createRequest($postfix);
        if ($this->_request->_c)
            $this->_controller = $this->_request->_c;
        if ($this->_request->_a)
            $this->_action = $this->_request->_a;
            $this->_app_namespace = WEB_NAMESPACE;
    }

    /**
     * 启动一个controller，执行指定的action方法，渲染controller/action模版
     * @throws Exception
     */
    public function run(){
        $response = new Response();
        $response->_controller = $this->_controller;
        $response->_action = $this->_action;

        $controller = $this->_app_namespace ."\\controller\\". $this->_controller.'controller';
        if (!class_exists($controller)){
            throw new BizException("no controller called $controller ");
        }

        $obj = new $controller($this->_request, $response);
        /**
         * 装饰器开始
         */
        $decoratorsArray=ConfigManage::getConfig('decorator');
        if(!empty($decoratorsArray))
        {
            /**
             * 全局装饰
             */
            if (isset($decoratorsArray['*']))
            {
                $_decorators=$decoratorsArray['*'];
                foreach ($_decorators as $decorator)
                {
                    $obj->addDecorator(new $decorator);
                }
            }
            if (isset($decoratorsArray[$this->_controller]))
            {
                $_decorators=$decoratorsArray[$this->_controller];
                if (isset($_decorators['*']))
                {
                    $_c_decorators=$_decorators['*'];
                    foreach ($_c_decorators as $decorator)
                    {
                        $obj->addDecorator(new $decorator);
                    }
                }
                if (isset($_decorators[$this->_action]))
                {
                    $_c_a_decorators=$_decorators[$this->_action];
                    foreach ($_c_a_decorators as $decorator)
                    {
                        $obj->addDecorator(new $decorator);
                    }
                }
            }
        }
        /**
         * 装饰器结束
         */

        $obj->befor($this->_controller, $this->_action);
        $obj->doAction($this->_action,$this->_request, $response);
        $obj->after($this->_controller, $this->_action);

    }
    /**
     * 把约定格式的url query string 转成静态地址
     * 为了便于管理，转换函数为原action函数前前辍 'url_'
     */
    public function urlfor($urlString) {
        $urls = parse_url($urlString);
        if (empty($urls['query']))
            return $urls;

        parse_str($urls['query'], $gets);
        $c = isset($gets['_c'])? $gets['_c']: 'index';
        $controller = $this->_app_namespace ."\\controller\\". $c.'controller';
        $a = isset($gets['_a'])? $gets['_a']: 'index';
        $action = 'url_'.$a;

        if (method_exists($controller, $action))
            return $controller::$action($gets);
        else
            return $urlString;
    }
}
