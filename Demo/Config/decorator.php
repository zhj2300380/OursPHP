<?php
/**
 * Created by PhpStorm.
 * User: zhaojin
 * Date: 2017/3/6
 * Time: 23:48
 * Doc:装饰器配置文件
 * 通過配置可以實現任意url前置以及後置裝飾器操作
 * 装饰器实例需要继承OursPHP\Core\Mvc\Controller\IDrawDecorator;
 * Demo
$options=array
(
    '*'=>//全局装饰器，任何controller的任何action全部都加载
        [
        new App\Decorator\ShowTimeDecorator()//装饰器内容继承OursPHP\Core\Mvc\Controller\IDrawDecorator;
        ],
    'index'=>//指定Controller装饰器，
        [
            '*'=>//当前Controller 的任意action全部加载
                [
                    new App\Decorator\ShowTimeDecorator()
                ]
        ],
    'index'=>//指定Controller装饰器，
        [
            'index'=>//指定当前Controller的index 犯法加载
                [
                    new App\Decorator\ShowTimeDecorator()
                ]
        ]
);
return $options;
 */
$options=array
(
    'index'=>
        [
            'index'=>
                [
                    App\Decorator\ShowTimeDecorator::class
                ]
        ]
);
return $options;