<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/5
 * Time: 20:08
 * Doc: Cookie操作类
 */
namespace OursPHP\Core\Lib\Cookie;

/**
 * Cookies class 保存,读取,更新,清除cookies数据。可设置前缀。强制超时。数据可以是字符串,数组,对象等。
 * Class CookieManage
 * @package OursPHP\Core\Lib\Cookie
 */
class CookieManage
{
    private $_prefix = '';                         // cookie prefix
    private $_securekey = OURS_SECUREKEY;          // encrypt key
    private $_expire = 0;
    private $_path='/';
    private $_domain='';
    private $_secure=false;


    /** 初始化
     * CookieManage constructor.
     * @param string $prefix
     * @param int $expire
     */
    public function __construct($prefix='', $expire=0){

        if(is_string($prefix) && !empty($prefix)){
            $this->_prefix = $prefix;
        }

        if(is_int($expire))
        {
            $this->_expire = $expire;
        }
    }
    /**
     * 设置加密KEY
     * @param string $securekey  加密字符串
     * @return $this 返回类本身实现链式操作
     */
    public function setSecureKey($securekey)
    {
        if(is_string($securekey) && !empty($securekey)){
            $this->_securekey = $securekey;
        }
        return $this;
    }
    /**
     * 是否强制启用https
     * @param bool $secure  true | false
     * @return $this 返回类本身实现链式操作
     */
    public function setSecure($secure)
    {
        if(is_bool($secure)){
            $this->_secure = $secure;
        }
        return $this;

    }
    /**
     * 设置作用域
     * @param string $domain 域名
     * @return $this 返回类本身实现链式操作
     */
    public function setDomain($domain)
    {
        if(is_string($domain) && !empty($domain)){
            $this->_domain = $domain;
        }
        return $this;
    }
    /**
     * 设置作用路径
     * @param string $path 路径
     * @return $this 返回类本身实现链式操作
     */
    public function setPath($path)
    {
        if(is_string($path) && !empty($path)){
            $this->_path = $path;
        }
        return $this;
    }
    /**
     * 设置前缀
     * @param string $prefix 前缀
     * @return $this 返回类本身实现链式操作
     */
    public function setPrefix($prefix)
    {
        if(is_string($prefix) && !empty($prefix)){
            $this->_prefix = $prefix;
        }
        return $this;
    }

    /**
     * 设置过期时间
     * @param int $expire 过期时间（秒）
     * @return $this 返回类本身实现链式操作
     */
    public function setExpire($expire)
    {
        if(is_int($expire))
        {
            $this->_expire = $expire;
        }
        return $this;
    }

    /**
     * 设置cookie
     * @param $name
     * @param $value
     * @param int $expire
     * @return bool
     */
    public function set($name, $value, $expire=0){
        $cookie_expire=$expire;
        $cookie_name = $this->getName($name);
        if($expire===0)
        {
            $cookie_expire=($this->_expire)>0?time()+$this->_expire:0;
        }
        if($expire>0)
        {
            $cookie_expire=time() + $expire;
        }
        $cookie_value = $this->pack($value, $cookie_expire);
        $cookie_value = $this->authcode($cookie_value, 'ENCODE');

        if($cookie_name && $cookie_value){
            return setcookie($cookie_name, $cookie_value, $cookie_expire,$this->_path,$this->_domain,$this->_secure);
        }
        return false;
    }

    /** 读取cookie
     * @param String $name  cookie name
     * @return mixed     cookie value
     */
    public function get($name){

        $cookie_name = $this->getName($name);

        if(isset($_COOKIE[$cookie_name])){

            $cookie_value = $this->authcode($_COOKIE[$cookie_name], 'DECODE');

            $cookie_value = $this->unpack($cookie_value);

            return isset($cookie_value[0])? $cookie_value[0] : null;

        }else{
            return null;
        }

    }

    /** 更新cookie,只更新内容,如需要更新过期时间请使用set方法
     * @param String $name  cookie name
     * @param mixed $value cookie value
     * @return boolean
     */
    public function update($name, $value){

        $cookie_name = $this->getName($name);

        if(isset($_COOKIE[$cookie_name])){

            $old_cookie_value = $this->authcode($_COOKIE[$cookie_name], 'DECODE');
            $old_cookie_value = $this->unpack($old_cookie_value);

            if(isset($old_cookie_value[1]) && $old_cookie_value[1]>0){ // 获取之前的过期时间

                $cookie_expire = $old_cookie_value[1];

                // 更新数据
                $cookie_value = $this->pack($value, $cookie_expire);
                $cookie_value = $this->authcode($cookie_value, 'ENCODE');

                if($cookie_name && $cookie_value && $cookie_expire){
                    return setcookie($cookie_name, $cookie_value, $cookie_expire,$this->_path,$this->_domain,$this->_secure);
                }
            }
        }
        return false;
    }

    /**
     * 清除cookie
     * @param $name
     * @return bool
     */
    public function clear($name){

        $cookie_name = $this->getName($name);
        return setcookie($cookie_name, null, -1,$this->_path,$this->_domain,$this->_secure);
    }


    /** 获取cookie name
     * @param String $name
     * @return String
     */
    private function getName($name){
        return $this->_prefix? $this->_prefix.'_'.$name : $name;
    }

    /** pack
     * @param string $data
     * @param $expire
     * @return string
     */
    private function pack($data='', $expire){

        if($data===''){
            return '';
        }

        $cookie_data = array();
        $cookie_data['value'] = $data;
        $cookie_data['expire'] = $expire;
        return json_encode($cookie_data);
    }

    /** unpack
     * @param Mixed $data 数据
     * @return       array(数据,过期时间)
     */
    private function unpack($data=''){

        if($data===''){
            return array('', 0);
        }

        $cookie_data = json_decode($data, true);

        if(isset($cookie_data['value']) && isset($cookie_data['expire'])){

            if(time()<$cookie_data['expire'] || $cookie_data['expire']==0){ // 未过期
                return array($cookie_data['value'], $cookie_data['expire']);
            }
        }
        return array('', 0);
    }

    /**
     * 加密/解密数据
     * @param string $string  原文或密文
     * @param string $operation ENCODE or DECODE
     * @return string 根据设置返回明文活密文
     */
    private function authcode($string, $operation = 'DECODE'){

        $ckey_length = 4;  // 随机密钥长度 取值 0-32;

        $key = $this->_securekey;

        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
}