<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/6
 * Time: 23:42
 * Doc:
 */
namespace  App\Decorator;

use  OursPHP\Core\Mvc\Controller\IDrawDecorator;

class ShowTimeDecorator implements IDrawDecorator
{
    function befor()
    {
        echo "<!-- Start:".time()."-->\r\n";
    }
    function after()
    {
        echo "\r\n<!-- End:".time()."-->";
    }
}