<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 0:51
 * Doc: 框架启动文件
 */
define('OURS_VERSION', '0.1.0');
define('OURS_DEBUG', false);
define('OURS_START_TIME', microtime(true));
define('OURS_START_MEM', memory_get_usage());
define('OURS_DS', DIRECTORY_SEPARATOR);
defined('OURS_PATH') or define('OURS_PATH', __DIR__ . OURS_DS);
define('OURS_AUTOLOAD_PATH', OURS_PATH . '..' . OURS_DS);
define('OURS_CORE_PATH', OURS_PATH . 'Core' . OURS_DS);
define('OURS_LIB_PATH', OURS_CORE_PATH . 'Lib' . OURS_DS);
defined('ROOT_PATH') or define('ROOT_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . OURS_DS);//documentroot目录
defined('WEB_PATH') or define('WEB_PATH', dirname(realpath(ROOT_PATH)) . OURS_DS);//当前站点所在的目录
defined('WEB_TMP_PATH') or define('WEB_TMP_PATH', WEB_PATH.'tmp' . OURS_DS);//当前站点模版缓存目录
defined('PROGECT_PATH') or define('PROGECT_PATH', dirname(realpath(WEB_PATH)) . OURS_DS);//当前项目所在目录
defined('PROGECT_CONFIG_PATH') or define('PROGECT_CONFIG_PATH', PROGECT_PATH.'Config' . OURS_DS);//当前项目配置文件所在目录
defined('PROGECT_APP_PATH') or define('PROGECT_APP_PATH',PROGECT_PATH . 'App'. OURS_DS);//项目公共资源目录存放项目内的公共code
defined('VENDOR_PATH') or define('VENDOR_PATH', PROGECT_PATH . 'vendor' . OURS_DS);//composer下载的扩展存在目录
define('INCLUDE_PATH', get_include_path());
define('OURS_START', microtime(true));

/*
 * 加载composer包
 */
if(file_exists(VENDOR_PATH.'autoload.php'))
{
    require VENDOR_PATH.'autoload.php';
}

/**
 * 框架自动加载
 * @param $class
 */
function ours_autoload( $class ) {
    $file = str_replace('\\','/',OURS_AUTOLOAD_PATH.$class . '.php');
    if (OURS_DEBUG) {
        echo '<!-- include '.$class.'-->';
    }
    if (is_file($file)) {
        require_once($file);
    }
}

/**
 * 项目自动加载
 * @param $class
 */
function progect_autoload( $class ) {
    $file = str_replace('\\','/',PROGECT_PATH.$class . '.php');
    if (OURS_DEBUG) {
        echo '<!-- include '.$class.'-->';
    }
    if (is_file($file)) {
        require_once($file);
    }
}

/**
 * 加载注册
 */
spl_autoload_register('ours_autoload');
spl_autoload_register('progect_autoload');

/**
 * 配置加载
 */
OursPHP\Init\ConfigManage::init();



use OursPHP\Core\Common\BizException;
use OursPHP\Core\Mvc\Http\Request;
use OursPHP\Core\Mvc\App;
use OursPHP\Core\Mvc\View\SmartyView;

function Start()
{
    /**
    $smarty = SmartyView::getSmarty();
    $smarty->left_delimiter = '{{';
    $smarty->right_delimiter = '}}';
    $smarty->template_dir = WEB_PATH . 'template'.OURS_DS;
    $smarty->cache_dir = WEB_TMP_PATH . "cache";
    $smarty->compile_dir = WEB_TMP_PATH . "templates_c";

//$smarty->registerClass('tpl', '\app\common\util\SmartyTpl'); //注册smarty 自定义函数类
    $smarty->error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED;
     * */
    $request = Request::getInstance();
    $controller = $request->_c;
    $action = $request->_a;

    $app_namespace = basename (WEB_PATH);//'website';

    $mvc = new App($controller, $action,$app_namespace);

    ob_start();
    try {
        $mvc->run();
    } catch (BizException $e) {
        throw $e;
    }

    ob_end_flush();
}