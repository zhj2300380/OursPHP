<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 18:38
 * Doc:获取指定配置节点的memcached实例
 */
namespace OursPHP\Core\Lib\Cache;

use OursPHP\Init\ConfigManage;
use OursPHP\Core\Common\BizException;
class Memcached {

    private static $_cachelist;

    private function __construct() {}

    /**
     * 获取指定配置节点的memcached实例
     * @param string $nodeName
     * @return \Memcached
     */
    public static function  getInstance($nodeName='default') {
        if(!isset(self::$_cachelist[$nodeName]))
        {
            $_memcached = new \Memcached();
            $options=ConfigManage::getConfig('memcached',$nodeName);
            if($options==false)
            {
                throw new BizException("memcached缓存相关节点未配置：".$nodeName);
            }
            $_memcached->addServers($options);
            self::$_cachelist[$nodeName]=$_memcached;
        }
        return self::$_cachelist[$nodeName];
    }
    public static function accessCache($key, $time, $get_data_func, $func_params=array(),$nodeName='default') {
        $_memcached=self::getInstance($nodeName);
        $data=$_memcached->get($key);
        if (empty($data) || isset($_GET['_refresh'])) {
            $data = call_user_func_array($get_data_func, $func_params);
            $_memcached->set($key, $data, $time);
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
        $_memcached=self::getInstance($nodeName);
        $data = $_memcached->get($key);

        if (empty($data) || isset($_GET['_refresh'])) {
            if($_memcached->add($key, null)) {
                $data = call_user_func_array($get_data_func, $func_params);
                if (!empty($data))
                    $_memcached->set($key, $data, $time);

            } else {
                for($i=0; $i<10; $i++) { //5秒没有反应，就出白页吧，系统貌似已经不行了
                    sleep(0.5);
                    $data = $_memcached->get($key);
                    if ($data !== false)
                        break;

                }
            }
        }
        return $data;
    }
}