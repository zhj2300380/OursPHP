<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/10
 * Time: 1:09
 * Doc:
 */
namespace OursPHP\Core\Mvc;
use OursPHP\Core\Mvc\Http\Request;
class Route
{
    public static function createRequest() {
        $request = Request::getInstance();

        if($request->_c)
        {
            /**
             * 有标准_c  _a模式就直接返回request
             */
            return $request;
        }

        /**
         * 没有_c和_a则返回restfull模式
         */
        $query = array();
        if(!$request->_c && isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']!='/')
        {
            $path=$_SERVER['REQUEST_URI'];
            $patharray=explode('/',trim($path,'/'));
            if(isset($patharray[0]))
            {
                $query['_c']=$patharray[0];
                unset($patharray[0]);
            }
            if(isset($patharray[1])){
                $query['_a']=$patharray[1];
                unset($patharray[1]);
            }else {
                $query['_a']='index';
            }
            $paramcount=count($patharray)+2;
            $i=2;
            while ($i <  $paramcount){
                if(isset($patharray[$i+1])) {
                    $query[$patharray[$i]]=$patharray[$i+1];
                }
                $i+=2;
            }
            $request->ModifyGet($query);
        }else{
            $query['_c']='index';
            $query['_a']='index';
        }
        return $request;
    }
}