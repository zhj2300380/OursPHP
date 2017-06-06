<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/10
 * Time: 2:58
 * Doc:
 */
namespace OursPHP\Init;

use OursPHP\Core\Common\BizException;
use OursPHP\Core\Mvc\App;
use OursPHP\Init\ConfigManage;
class WebManage{
    public static function  webStart(){
        $postfix=ConfigManage::getConfig('route',WEB_NAMESPACE);
        $mvc = new App($postfix);
        ob_start();
        try {
            $mvc->run();
        } catch (BizException $e) {
            throw $e;
        }
        ob_end_flush();
    }
}