<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 10:43
 * Doc: 配置信息加载类
 */
namespace OursPHP\Init;

use OursPHP\Core\Common\BizException;
class ConfigManage
{
    private static $_configList;

    private static $_configType=['mysql','mongodb','memcached','redis','decorator'];

    /**
     * @param $type 'mysql','mongodb','memcached','redis'
     * @param $nodeName 節點名稱
     * @return mixed
     * @throws BizException
     */
    public static function getConfig($type,$nodeName=null)
    {
        if (!in_array($type,self::$_configType))
        {
            throw new BizException("未知的配置类型");
        }
        if($nodeName && !key_exists($nodeName, self::$_configList[$type]))
        {
            throw new BizException("数据节点不存在");
        }
        return ($nodeName)?self::$_configList[$type][$nodeName]:self::$_configList[$type];
    }
    public static function init()
    {
        $filepath=PROGECT_CONFIG_PATH.'mysql.php';
        if (file_exists($filepath))
        {
            self::$_configList['mysql']=include($filepath);
        }
        $filepath=PROGECT_CONFIG_PATH.'mongo.php';
        if (file_exists($filepath))
        {
            self::$_configList['mongo']=include($filepath);
        }
        $filepath=PROGECT_CONFIG_PATH.'memcached.php';
        if (file_exists($filepath))
        {
            self::$_configList['memcached']=include($filepath);
        }
        $filepath=PROGECT_CONFIG_PATH.'redis.php';
        if (file_exists($filepath))
        {
            self::$_configList['redis']=include($filepath);
        }
        $filepath=PROGECT_CONFIG_PATH.'decorator.php';
        if (file_exists($filepath))
        {
            self::$_configList['decorator']=include($filepath);
        }
    }
}