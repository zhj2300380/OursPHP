<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/8
 * Time: 1:26
 * Doc:
 */
namespace  manage\controller;

use OursPHP\Core\Mvc\Controller\Controller;
use OursPHP\Core\Lib\Cookie\CookieManage;
use OursPHP\Core\Lib\Pagination\PageLinkManage;
use App\Service\SystemSvc;

class BaseController extends Controller
{
    protected $_manager;
    protected $_rolelist=[];
    protected $_menu;

    /**
     * BaseController constructor.
     * @param $request
     * @param $response
     */
    public function __construct($request, &$response)
    {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct($request, $response);
        $cookieManage=new CookieManage('manager');
        $this->_manager=$cookieManage->get('info');
        if (!$this->_manager || $this->_manager['status']==0)
        {
            $this->redirect('/login');
        }
        $response->me=$this->_manager;
        $roleid=$this->_manager['roleid'];
        $svc=new SystemSvc();
        $response->menulist=$this->_menu=$svc->getFunctionMap_with_cache('cache_time=120');

        $role=$svc->getRole_with_cache('cache_time=120',$roleid);
        $this->_rolelist=($role['status']==1)?json_decode($role['functionlist'],true):[];
        $response->rolelist=$this->_rolelist;
        if(!array_key_exists(strtolower($this->_controller), $this->_rolelist) || !in_array(strtolower($this->_action), $this->_rolelist[strtolower($this->_controller)]))
        {
            $response->title="权限验证";
            $response->stitle="未授权";
            $this->layoutSmarty('../layout/nopower');
            exit;
        }
        $this->pageLink = PageLinkManage::getInstance();
    }
    protected static function getOffset($pid, $pageSize) {
        $offset = $pageSize*($pid-1);
        return $offset<0?'0,'.$pageSize:$offset.','.$pageSize;
    }
}