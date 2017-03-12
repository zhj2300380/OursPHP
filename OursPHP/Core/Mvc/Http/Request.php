<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 1:01
 * Doc: Request
 */
namespace OursPHP\Core\Mvc\Http;
use OursPHP\Core\Common\BizException;

/**
 * http request
 *
 */
class Request {

    private $_openfilter=true;
    private static $_instance;
    private $allowModify = false;
    private static $getfilter="'|\\b(and|or)\\b.+?(>|<|=|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    private static $postfilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    private static $cookiefilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    const REPLACEMENT = '';

    private function __construct()
    {
        $this->_openfilter=OURS_REQUEST_FILTER_IMPUT;
    }

    /**
     * 设置是否开启输入过滤
     * 默认根据全局变量来配置
     * $request->setOpenFilter()->content;
     * @param bool $open 是否开启
     * @return $this
     */
    public function setOpenFilter($open=true)
    {
        $this->_openfilter=$open;
        return $this;
    }
    /**
     * 单例
     * @return Request
     */
    public static function getInstance() {
        if (!self::$_instance)
            self::$_instance = new self();
        return self::$_instance;
    }

    /**
     * 返回 $_GET[$index] | default
     * @param string $index
     * @param string $default 没有取到的时候的默认值
     * @return string
     */
    public function get($index, $default='') {
        if (isset($_GET[$index])) {
            $data=$this->filter_input($_GET[$index]);
            return preg_replace("/".self::$getfilter."/is", self::REPLACEMENT, $data);
        } else {
            return $default;
        }
    }

    /**
     * @return string 获取PHP输入流
     */
    public function getInputStream()
    {
        return file_get_contents("php://input");
    }
    /**
     * 返回 $_POST[$index] | default
     * @param string $index
     * @param string $default 没有取到的时候的默认值
     * @return string
     */
    public function post($index, $default='') {
        if (isset($_POST[$index])) {
            $data=$this->filter_input($_POST[$index]);
            return preg_replace("/".self::$postfilter."/is", self::REPLACEMENT, $data);
        } else {
            return $default;
        }
    }

    /**
     * 返回 $_REQUEST[$index] | default
     * @param string $index
     * @param string $default 没有取到的时候的默认值
     * @return string
     */
    public function getRequest($index, $default='') {
        if (isset($_REQUEST[$index])) {
            $data=$this->filter_input($_REQUEST[$index]);
            $data = preg_replace("/".self::$getfilter."/is", self::REPLACEMENT, $data);
            return preg_replace("/".self::$postfilter."/is", self::REPLACEMENT, $data);
        } else {
            return $default;
        }
    }

    /**
     * $_COOKIE[$index]
     * @param string $index
     * @return array|null <string, string>
     */
    public function getCookie($index) {
        return isset($_COOKIE[$index]) ? preg_replace("/" . self::$cookiefilter . "/is", self::REPLACEMENT, $_COOKIE[$index]) : null;
    }

    /**
     * 设置$_GET可以被修改，特殊情况下使用
     * @param array $get
     */
    public function ModifyGet(array $get) {
        $this->allowModify = true;

        foreach ($get as $k=>$v)
        {
            $v=$this->filter_input($v);
            $v = preg_replace("/".self::$getfilter."/is", self::REPLACEMENT, $v);
            $this->$k = $v;
        }
        $this->allowModify = false;
    }

    /**
     * 用对象属性方式直接调用
     * @param string $key
     * @return string
     */
    public function __get($key) {
        return $this->getRequest($key);
    }

    /**
     * 用对象属性方式直接调用
     * @param $k
     * @param $v
     * @throws BizException
     */
    public function __set($k, $v) {
        if ($this->allowModify)
            $this->$k = $v;
        else
            throw new BizException('set value to const!');

    }

    /**
     * 表单过滤
     * @param $data
     * @return string
     */
    function filter_input($data='') {
        if($this->_openfilter)
        {
            if(is_array($data))
            {
                foreach ($data as &$item)
                {
                    if(!is_array($item))
                    {
                        $item = trim($item);
                        $item = stripslashes($item);
                        $item = htmlspecialchars($item);
                    }
                }
            }
            else
                {
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                }
        }
        return $data;
    }
}