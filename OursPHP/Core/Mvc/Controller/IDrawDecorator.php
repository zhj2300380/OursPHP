<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/6
 * Time: 23:23
 * Doc:控制器装饰器
 */
namespace OursPHP\Core\Mvc\Controller {

    interface IDrawDecorator
    {
        function befor();
        function after();
    }
}