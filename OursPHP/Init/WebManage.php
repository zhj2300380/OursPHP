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
class WebManage{
    public static function  webStart(){
        $mvc = new App();
        ob_start();
        try {
            $mvc->run();
        } catch (BizException $e) {
            throw $e;
        }
        ob_end_flush();
    }
}