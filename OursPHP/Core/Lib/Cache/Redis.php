<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 18:38
 * Doc: 获取指定配置节点的redis实例
 */
namespace OursPHP\Core\Lib\Cache;
use OursPHP\Init\ConfigManage;

class Redis {

    private static $_redis;

    private function __construct() {}

    /**
     * 获取指定配置节点的redis实例
     * @param string $nodeName
     * @return \Redis
     */
    public static function  getInstance($nodeName='default') {
        if (empty(self::$_redis)) {
            self::$_redis = new \Redis();
            list($host, $port,$passwd,$dbindex)=ConfigManage::getConfig('redis',$nodeName);
            self::$_redis->pconnect($host,$port);
            if(!empty($passwd))
            {
                self::$_redis->auth($passwd);
            }
            self::$_redis->select($dbindex);
        }
        return self::$_redis;
    }
}