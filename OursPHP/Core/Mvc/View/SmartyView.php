<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 1:24
 * Doc: SmartView
 */
namespace OursPHP\Core\Mvc\View;

use OursPHP\Core\Common\DataAssert;
use OursPHP\Core\Common\BizException;
use OursPHP\Core\Mvc\iView;
use \Smarty;

class SmartyView implements iView {

    private $_response;
    private $_template;
    private static $_smarty;
    private $_layoutDir = 'layout';

    public function __construct($response) {
        $this->_response = $response;
        self::$_smarty = self::getSmarty();
        self::$_smarty->left_delimiter = '{{';
        self::$_smarty->right_delimiter = '}}';
        self::$_smarty->template_dir = WEB_PATH . 'template'.OURS_DS;
        self::$_smarty->cache_dir = WEB_TMP_PATH . "cache";
        self::$_smarty->compile_dir = WEB_TMP_PATH . "templates_c";
        self::$_smarty->error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED;
        if(!file_exists(WEB_TMP_PATH))
        {
            mkdir(WEB_TMP_PATH,755);
        }
    }

    /**
     * 取得当前的smary
     * @return \Smarty
     */
    public static function getSmarty() {
        //include YEPF_PATH . "/core/smarty/Smarty.class.php";

        if (!self::$_smarty) {
            include OURS_CORE_PATH.'Plugin/Smarty/Smarty.class.php';
            DataAssert::assertTrue(class_exists('Smarty'),new BizException('没有包含smarty类库'));
            self::$_smarty = new Smarty();
        }
        return self::$_smarty;
    }


    /**
     * smary assign
     * @param string $varname
     * @param array $var
     */
    public function assign($varname, $var)	{
        self::$_smarty->assign($varname,$var);
    }

    /**
     * 渲染smarty模版文件
     * @param string $file 指定的模版文件
     */
    public function render($file) {
        foreach (get_object_vars($this->_response) as $key=>$value) {
            self::$_smarty->assign($key, $value);
        }
        //要用ob_start不能用display
        //self::$_smarty->display($file);
        echo self::$_smarty->fetch($file);
    }

    /**
     * 渲染smarty模版布局文件
     * @param string $action_file 指定的布局文件
     */
    public function layout($layoutFile) {
        foreach (get_object_vars($this->_response) as $key=>$value) {
            self::$_smarty->assign($key, $value);
        }
        $layout_template =  $this->_layoutDir.'/'.$layoutFile.'.html';
        //要用ob_start不能用display
        //self::$_smarty->display($layout_template);
        echo self::$_smarty->fetch($layout_template);
    }
}
