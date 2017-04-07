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

    private static $_configType=['mysql','mongodb','memcached','redis','decorator','route','upload','pagination','withcache'];

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
            throw new BizException($type.":未知的配置类型");
        }
        if($nodeName && !key_exists($nodeName, self::$_configList[$type]))
        {
            return false;
        }
        return ($nodeName)?self::$_configList[$type][$nodeName]:self::$_configList[$type];
    }
    public static function init()
    {
        foreach (self::$_configType as $item)
        {
            $filepath=PROJECT_CONFIG_PATH."$item.php";
            if (file_exists($filepath))
            {
                self::$_configList[$item]=include($filepath);
            }
        }
    }
}