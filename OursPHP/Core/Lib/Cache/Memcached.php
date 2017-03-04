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
class Memcached {

    private static $_memcached;

    private function __construct() {}

    /**
     * 获取指定配置节点的memcached实例
     * @param string $nodeName
     * @return \Memcached
     */
    public static function  getInstance($nodeName='default') {
        if (empty(self::$_memcached)) {
            self::$_memcached = new \Memcached();
            $options=ConfigManage::getConfig('memcached',$nodeName);
            self::$_memcached->addServers($options);
        }
        return self::$_memcached;
    }
}