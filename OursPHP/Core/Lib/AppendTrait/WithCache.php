<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/6
 * Time: 14:35
 * Doc:
 */
namespace OursPHP\Core\Lib\AppendTrait;

use OursPHP\Core\Common\BizException;
use OursPHP\Core\Lib\Cache\Memcached;
use OursPHP\Core\Lib\Cache\Redis;
trait WithCache
{
    protected static $_cache_time = 600;

    /**
     * @param $name
     * @param $params
     * @return mixed
     * @throws BizException
     */
    public function __call($name, $params)
    {
        if (substr($name, -11) == '_with_cache') {
            $cache_time = static::$_cache_time;
            $relFunc = substr($name, 0, strlen($name)-11);
            $key = md5($name.serialize($params));
            $cache_time_params = isset($params[0])? $params[0]:null;
            if (is_string($cache_time_params) && substr($cache_time_params, 0, 11) == 'cache_time=') {
                $cache_time = intval(substr($cache_time_params, 11));
                array_shift($params); //第一个参数是缓存时间不是函数用的，去除
            }
            $cache_key_params = isset($params[0])? $params[0]:null;
            if (is_string($cache_key_params) && substr($cache_key_params, 0, 10) == 'cache_key=') {
                $key = substr($cache_key_params, 10);
                array_shift($params); //第一个参数是缓存时间不是函数用的，去除
            }
            try
            {
                $data = Memcached::accessCache($key, $cache_time, array($this, $relFunc), $params);
                return $data;
            }catch (BizException $ex)
            {
                //throw $ex;
            }
            try
            {
                $data = Redis::accessCache($key, $cache_time, array($this, $relFunc), $params);
                return $data;
            }catch (BizException $ex)
            {
                //throw $ex;
            }
            return call_user_func_array(array($this, $relFunc), $params);
        }
    }
}