<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 1:22
 * Doc: BaeseController
 * web模式和API模式
 */
namespace OursPHP\Core\Mvc\Controller;

use OursPHP\Core\Common\BizException;
use OursPHP\Core\Mvc\View\SmartyView;

class Controller
{
    /**
     * @var string 控制器類型
     */
    protected $_app_type='web';
	//装饰器
	private $_decorators=array();
	//当前controler的默认layout,子类可以重写
	public $_response;

	protected $_request;
	protected $_controller;
	protected $_action;

	private $_templateDir;
	private $_layoutDir;
	private $_layout;

	public function __construct($request, &$response) {
        $this->_request = $request;
        $this->_response = $response;
        $this->_controller = $response->_controller;
        $this->_action = $response->_action;
        $this->_templateDir = '../template/';
        $this->_layout = 'default';
    }

    /**
     * 设置为api访问模式
     */
    protected function isApi()
    {
        $this->_app_type='api';
    }
	/**
     * 设置默认的布局器
     * @param unknown_type $layout
     */
	protected function setLayout($layout){
        $this->_layout = $layout;
    }

    public function doAction($action='index',$request, $response)
    {
        if($this->_app_type==='api')
        {
            $_method=$_SERVER['REQUEST_METHOD'];
            $action=$_method.'_'.$action;
        }
        if (!method_exists($this, $action))
        {
            throw new BizException("'{$this->_controller}' has not method '{$action}' ");
        }
        $this->{$action}($request, $response);
    }
	/**
     * 前置装饰器
     */
	public function befor()
    {
        foreach ($this->_decorators as $decorator)
        {
            $decorator->befor();
        }
    }

	/**
     * 后置装饰器
     */
	public function after()
    {
        $decorators=array_reverse($this->_decorators);
        foreach ($decorators as $decorator)
        {
            $decorator->after();
        }
    }
    /**
     * 添加装饰器
     * @param IDrawDecorator $decorator
     */
    public function addDecorator(IDrawDecorator $decorator)
    {
        $this->_decorators[]=$decorator;
    }

	/**
     * 渲染模版文件
     * @param string $file 指定的模版文件
     */
	protected function render($file=NULL) {
        foreach (get_object_vars($this->_response) as $key=>$value) {
            $$key = $value;
        }

        $controller = strtolower($this->_controller);
        $action = strtolower($this->_action);

        if ($file)
            $action = $file;

        $path = str_replace('\\', '/', $controller);
        $template = $this->_templateDir.$path.'/'.$action.'.php';
        include $template;
    }

	/**
     * 渲染模版布局文件
     * @param string $action_file 指定的布局文件
     */
	protected function layout($action_file=NULL) {
        foreach (get_object_vars($this->_response) as $key=>$value) {
            $$key = $value;
        }

        $controller = strtolower($this->_controller);
        $action = strtolower($this->_action);

        if (!$action_file)
            $action_file = $action;

        $_layout_content = $this->_templateDir.$controller.'/'.$action_file.'.php';

        $layout_template =   $this->_templateDir.'layout/'.$this->_layout.'.php';
        include strtolower($layout_template);
    }

	/**
     * 输出json
     * @param obj $array_or_obj
     */
	public function renderJson($array_or_obj) {
        header('Content-type: application/json');
        echo json_encode($array_or_obj);
    }

	/**
     * 输出字符串
     * @param string $string
     */
	public function renderString($string) {
        echo $string;
    }


	/**
     * 跳转 $url
     * @param string $url
     */
	public function redirect($url) {
        header("Location: $url");
    }


	/**
     * 渲染smarty模版文件
     * @param string $file 指定的模版文件
     */
	public function renderSmarty($template_file =NULL) {
        if( !$template_file ){
            $template_file = $this->_action;
        }
        //get action template file path
        $action_template = $this->_controller .'/'. $template_file .'.html';
        $smarty =  new SmartyView($this->_response);
        $smarty->render(strtolower($action_template));
    }

	/**
     * 渲染smarty模版布局文件
     * @param string $action_file 指定的布局文件
     */
	public function layoutSmarty($template_file = NULL) {
        if( !$template_file ){
            $template_file = $this->_action;
        }
    //主action相关内容
        $path = str_replace('\\', '/', $this->_controller);
        $action_template = $path.'/'. $template_file .'.html';
        $this->_response->_layout_content = strtolower($action_template);

        $smarty =  new SmartyView($this->_response);
        $smarty->layout(strtolower($this->_layout));
    }


	protected function isPost() {
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST';
    }

	public function goBack() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
