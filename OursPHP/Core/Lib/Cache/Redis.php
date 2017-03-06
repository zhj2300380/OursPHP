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

    /**
     * notice 本缓存不会存 [0 false null] 不加锁
     * @param $key
     * @param $time
     * @param $get_data_func
     * @param array $func_params
     * @return mixed
     */
    public static function accessCache($key, $time, $get_data_func, $func_params=array()) {
        self::getInstance();
        $data = self::$_redis->get($key);
        if (empty($data) || isset($_GET['_refresh'])) {
            $data = call_user_func_array($get_data_func, $func_params);
            self::$_redis->set($key, $data, $time);
        }
        return $data;
    }

    /**
     * 本缓存不会存 [0 false null] 加锁
     * @param $key
     * @param $time
     * @param $get_data_func
     * @param array $func_params
     * @return mixed
     */
    public static function accessCacheWithLock($key, $time, $get_data_func, $func_params=array()) {
        self::getInstance();
        $data = self::$_redis->get($key);

        if ($data && empty($_GET['_refresh']))
            return $data;
        else
            self::$_redis->delete($key);

        //防止并发取缓存
        if(self::$_redis->setnx($key, null)) {
            $data = call_user_func_array($get_data_func, $func_params);
            if (!empty($data))
                self::$_redis->set($key, $data, $time);

        } else {
            for($i=0; $i<10; $i++) { //5秒没有反应，就出白页吧，系统貌似已经不行了
                sleep(0.5);
                $data = self::$_redis->get($key);
                if ($data !== false)
                    break;

            }
        }
        return $data;
    }
}