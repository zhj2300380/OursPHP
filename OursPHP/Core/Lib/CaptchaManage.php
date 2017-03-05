<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/5
 * Time: 20:48
 * Doc: 驗證碼操作類
 */
namespace OursPHP\Core\Lib;

class CaptchaManage
{
    private $_charset = OURS_CHARSET;//随机因子
    private $_code;//验证码
    private $_codelen = 6;//验证码长度
    private $_width = 130;//宽度
    private $_height = 50;//高度
    private $_img;//图形资源句柄
    private $_font;//指定的字体
    private $_fontsize = 20;//指定字体大小
    private $_fontcolor;//指定字体颜色
    private $_expire=300;//有效期


    private static $_captchamanage;

    /**
     * 单列
     * @param int $expire 有效期（秒）
     * @param int $codelen 验证码长度
     * @param int $width 宽度
     * @param int $height 高度
     * @param int $fontsize 指定字体大小
     * @return CaptchaManage
     */
    public static function  getInstance($expire=300,$codelen=6,$width=130,$height=50,$fontsize=20) {
        if (empty(self::$_captchamanage)) {
            self::$_captchamanage = new self($expire,$codelen,$width,$height,$fontsize);
        }
        return self::$_captchamanage;
    }

    /**
     * CaptchaManage constructor.
     * @param int $codelen 验证码长度
     * @param int $width 宽度
     * @param int $height 高度
     * @param int $fontsize 指定字体大小
     */
    public function __construct($expire=300,$codelen=6,$width=130,$height=50,$fontsize=20) {

        if(is_int($expire) && $expire>0){
            $this->_expire = $expire;
        }
        if(is_int($codelen) && $codelen>0){
            $this->_codelen = $codelen;
        }

        if(is_int($width) && $width>0){
            $this->_width = $width;
        }

        if(is_int($height) && $height>0){
            $this->_height = $height;
        }
        if(is_int($fontsize) && $height>0){
            $this->_fontsize = $fontsize;
        }
        $this->_font = OURS_LIB_PATH.'/Captcha/font/Elephant.ttf';//注意字体路径要写对，否则显示不了图片
    }
    //生成随机码
    private function createCode() {
        $_len = strlen($this->_charset)-1;
        for ($i=0;$i<$this->_codelen;$i++) {
            $this->_code .= $this->_charset[mt_rand(0,$_len)];
        }
        CookieManage::getInstance('ck')->set('cc',$this->getCode(),$this->_expire);
    }
    //生成背景
    private function createBg() {
        $this->_img = imagecreatetruecolor($this->_width, $this->_height);
        $color = imagecolorallocate($this->_img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->_img,0,$this->_height,$this->_width,0,$color);
    }
    //生成文字
    private function createFont() {
        $_x = $this->_width / $this->_codelen;
        for ($i=0;$i<$this->_codelen;$i++) {
            $this->_fontcolor = imagecolorallocate($this->_img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imagettftext($this->_img,$this->_fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->_height / 1.4,$this->_fontcolor,$this->_font,$this->_code[$i]);
        }
    }
    //生成线条、雪花
    private function createLine() {
        //线条
        for ($i=0;$i<6;$i++) {
            $color = imagecolorallocate($this->_img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->_img,mt_rand(0,$this->_width),mt_rand(0,$this->_height),mt_rand(0,$this->_width),mt_rand(0,$this->_height),$color);
        }
        //雪花
        for ($i=0;$i<100;$i++) {
            $color = imagecolorallocate($this->_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
            imagestring($this->_img,mt_rand(1,5),mt_rand(0,$this->_width),mt_rand(0,$this->_height),'*',$color);
        }
    }

    /**
     * 輸出圖片
     */
    private function outPut() {
        header('Content-type:image/png');
        imagepng($this->_img);
        imagedestroy($this->_img);
    }

    /**
     * 生成驗證碼
     */
    public function doimg() {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    /**
     * 验证
     * @param $code
     * @return bool
     */
    public function check($code='')
    {
        $rel=false;
        $this->_code=CookieManage::getInstance('ck')->get('cc');
        if($code===$this->_code)
        {
            $rel=true;
        }
        return $rel;
    }

    /**
     * 获取验证码内容
     * @return string
     */
    public function getCode() {
        return strtolower($this->_code);
    }
}