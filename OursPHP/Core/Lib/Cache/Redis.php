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
use OursPHP\Core\Common\BizException;

class Redis {

    private static $_cachelist;

    private function __construct() {}

    /**
     * 获取指定配置节点的redis实例
     * @param string $nodeName
     * @return \Redis
     */
    public static function  getInstance($nodeName='default') {
        if(!isset(self::$_cachelist[$nodeName]))
        {
            $_redis = new \Redis();
            $options=ConfigManage::getConfig('redis',$nodeName);
            if($options==false)
            {
                throw new BizException("redis缓存相关节点未配置：".$nodeName);
            }
            list($host, $port,$passwd,$dbindex)=$options;
            $_redis->pconnect($host,$port);
            if(!empty($passwd))
            {
                $_redis->auth($passwd);
            }
            if(is_numeric($dbindex) && $dbindex>0)
            {
                $_redis->select($dbindex);
            }
            self::$_cachelist[$nodeName]=$_redis;
        }
        return self::$_cachelist[$nodeName];
    }

    /**
     * notice 本缓存不会存 [0 false null] 不加锁
     * @param $key
     * @param $time
     * @param $get_data_func
     * @param array $func_params
     * @return mixed
     */
    public static function accessCache($key, $time, $get_data_func, $func_params=array(),$nodeName='default') {
        $_redis=self::getInstance($nodeName);
        $data = $_redis->get($key);
        if (empty($data) || isset($_GET['_refresh'])) {
            $data = call_user_func_array($get_data_func, $func_params);

            if(is_object($data)||is_array($data)){
                $data = serialize($data);
            }
            $_redis->set($key, $data, $time);
        }
        $data_serl = @unserialize($data);
        if(is_object($data_serl)||is_array($data_serl)){
            $data= $data_serl;
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
    public static function accessCacheWithLock($key, $time, $get_data_func, $func_params=array(),$nodeName='default') {
        $_redis=self::getInstance($nodeName);
        $data = $_redis->get($key);

        if (empty($data) || isset($_GET['_refresh'])) {
            if($_redis->setnx($key, null)) {
                $data = call_user_func_array($get_data_func, $func_params);

                if (!empty($data))
                {
                    if(is_object($data)||is_array($data)){
                        $data = serialize($data);
                    }
                    $_redis->set($key, $data, $time);
                }

            } else {
                for($i=0; $i<10; $i++) { //5秒没有反应，就出白页吧，系统貌似已经不行了
                    sleep(0.5);
                    $data = $_redis->get($key);
                    if ($data !== false)
                        break;

                }
            }
        }
        $data_serl = @unserialize($data);
        if(is_object($data_serl)||is_array($data_serl)){
            $data= $data_serl;
        }
        return $data;
    }
}