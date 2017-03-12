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
    /**
     * @param string $postfix
     * @return Request 伪静态后缀包括.
     */
    public static function createRequest($postfix='') {
        $request = Request::getInstance();
        if($request->_c)
        {
            /**
             * 有标准_c  _a模式就直接返回request
             */
            return $request;
        }

        /**
         * 没有_c和_a则url属于restfull模式
         */
        $query = array();
        if(!$request->_c && isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']!='/')
        {
            $requesturi=$_SERVER['REQUEST_URI'];
            $requestarray=parse_url($requesturi);
            $paramstr=isset($requestarray['query'])?$requestarray['query']:false;
            if($paramstr)
            {
                parse_str($paramstr,$param);
                $request->ModifyGet($param);
            }
            $requestpath=$requestarray['path'];
            $postfixindexof=strripos($requestpath,$postfix);
            if(!empty($postfix) && $postfixindexof!=false){
                $requestpath=substr($requestpath,0,$postfixindexof);
            }
            $patharray=explode('/',trim($requestpath,'/'));
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