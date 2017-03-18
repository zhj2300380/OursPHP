<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/13
 * Time: 23:12
 * Doc:
 */
namespace  manage\controller;

use App\Dao\System_Function;
use App\Dao\System_Manager;
use App\Dao\System_Role;
use App\Service\SystemSvc;
use OursPHP\Core\Mvc\Controller\Controller;
use manage\controller\BaseController;

class SystemController  extends BaseController
{
    public function __construct($request, &$response) {
        header("Content-type: text/html; charset=utf-8");
        $response->title='系统设置';
        parent::__construct($request, $response);
    }

    public function functionsetmenu($request)
    {
        $id=$request->id;
        if($id)
        {
            $dao=new System_Function();
            $dao->ismenu($id);
        }
        $this->renderString('ok');
    }
    public function functiondel($request,$response)
    {
        $id=$request->id;
        if($id)
        {
            $svc=new SystemSvc();
            $rows=$svc->getFunctions($id);
            if(count($rows)==0)
            {
                $dao=new System_Function();
                $dao->delete($id);
                $this->renderString('ok');
            }else
            {
                $this->renderString('存在子功能，禁止删除');
            }
        }else
        {
            $this->renderString('ok');
        }

    }
    public function functionlock($request)
    {
        $id=$request->id;
        if($id)
        {
            $dao=new System_Function();
            $dao->lock($id);
        }
        $this->renderString('ok');
    }
    public function functionList($request,$response) {

        $response->stitle='系统功能管理';

        $svc=new SystemSvc();
        $rootMenu=$svc->getFunctions();
        if(!empty($rootMenu))
        {
            foreach ($rootMenu as &$fun)
            {
                $fun['sub']=$svc->getFunctions($fun['id']);
            }
        }
        $response->rootMenu=$rootMenu;
        $this->layoutSmarty();
    }
    public function functionAdd($request,$response)
    {
        $response->stitle='功能添加';
        if($request->id)
        {
            $response->stitle='功能编辑';
            $id=$request->id;
            $svc=new SystemSvc();
            $item=$svc->getFunction($id);
            $response->item=$item;
        }

        if($this->isPost())
        {
            $vars=$request->vars;
            if(isset($vars['ismenu']))
            {
                $vars['ismenu']=1;
            }
            $vars['type']=($vars['pid']==0)?0:1;
            $svc=new SystemSvc();

            if(isset($id))
            {
                $svc->editFunction($id,$vars);
            }else
            {
                $svc->addFunction($vars);
            }

            $this->redirect('/system/functionlist');
        }

        //icon start
        $icon_fa='fa-adjust,fa-asterisk,fa-ban,fa-bar-chart-o,fa-barcode,fa-flask,fa-beer,fa-bell-o,fa-bell,fa-bolt,fa-book,fa-bookmark,fa-bookmark-o,fa-briefcase,fa-bullhorn,fa-calendar,fa-camera,fa-camera-retro,fa-certificate,fa-check-square-o,fa-square-o,fa-circle,fa-circle-o,fa-cloud,fa-cloud-download,fa-cloud-upload,fa-coffee,fa-cog,fa-cogs,fa-comment,fa-comment-o,fa-comments,fa-comments-o,fa-credit-card,fa-tachometer,fa-desktop,fa-arrow-circle-o-down,fa-download,fa-pencil-square-o,fa-envelope,fa-envelope-o,fa-exchange,fa-exclamation-circle,fa-external-link,fa-eye-slash,fa-eye,fa-video-camera,fa-fighter-jet,fa-film,fa-filter,fa-fire,fa-flag,fa-folder,fa-folder-open,fa-folder-o,fa-folder-open-o,fa-cutlery,fa-gift,fa-glass,fa-globe,fa-users,fa-hdd-o,fa-headphones,fa-heart,fa-heart-o,fa-home,fa-inbox,fa-info-circle,fa-key,fa-leaf,fa-laptop,fa-gavel,fa-lemon-o,fa-lightbulb-o,fa-lock,fa-unlock';
        $icon_glyphicon='glyphicon-asterisk,glyphicon-plus,glyphicon-euro,glyphicon-minus,glyphicon-cloud,glyphicon-envelope,glyphicon-pencil,glyphicon-glass,glyphicon-music,glyphicon-search,glyphicon-heart,glyphicon-star,glyphicon-star-empty,glyphicon-user,glyphicon-film,glyphicon-th-large,glyphicon-th,glyphicon-th-list,glyphicon-ok,glyphicon-remove,glyphicon-zoom-in,glyphicon-zoom-out,glyphicon-off,glyphicon-signal,glyphicon-cog,glyphicon-trash,glyphicon-home,glyphicon-file,glyphicon-time,glyphicon-road,glyphicon-download-alt,glyphicon-download,glyphicon-upload,glyphicon-inbox,glyphicon-play-circle,glyphicon-repeat,glyphicon-refresh,glyphicon-list-alt,glyphicon-lock,glyphicon-flag,glyphicon-headphones,glyphicon-volume-off,glyphicon-volume-down,glyphicon-volume-up,glyphicon-qrcode,glyphicon-barcode,glyphicon-tag,glyphicon-tags,glyphicon-book,glyphicon-bookmark,glyphicon-print,glyphicon-camera,glyphicon-font,glyphicon-bold,glyphicon-italic,glyphicon-text-height,glyphicon-text-width,glyphicon-align-left,glyphicon-align-center,glyphicon-align-right,glyphicon-align-justify,glyphicon-list,glyphicon-indent-left,glyphicon-indent-right,glyphicon-facetime-video,glyphicon-picture,glyphicon-map-marker,glyphicon-adjust,glyphicon-tint,glyphicon-edit,glyphicon-share,glyphicon-check,glyphicon-move,glyphicon-step-backward,glyphicon-fast-backward,glyphicon-backward';
        $iconarray['glyphicon']=explode(',',$icon_glyphicon);
        $iconarray['fa']=explode(',',$icon_fa);
        $response->icons=$iconarray;
        //icon end
        //隶属 start
        $svc=new SystemSvc();
        $functionrows=$svc->getFunctions(0,1);

        //dump($functionrows);exit;
        $baserow=['id'=>0,'name'=>'根节点','icon'=>'','type'=>0,'description'=>'根节点','ismenu'=>0];
        array_unshift($functionrows,$baserow);
        $response->functionrows=$functionrows;
        //隶属
        $this->layoutSmarty();
    }


    public function roleAdd($request,$response)
    {
        $response->stitle='创建新角色';

        if($request->id)
        {
            $response->stitle='编辑角色信息';
            $id=$request->id;
            $svc=new SystemSvc();
            $item=$svc->getRole($id);
            $item['functionlist']=json_decode($item['functionlist'],true);
            //dump($item);
            $response->item=$item;
        }
        if($this->isPost())
        {

            $vars=$request->vars;
            $funarray=[];
            $svc=new SystemSvc();
            $functions=$svc->getFunctions();
            if(!empty($functions))
            {
                foreach ($functions as $fun)
                {
                    $powers=$request->$fun['uri'];
                    if(!empty($powers))
                    {
                        if(is_string($powers))
                        {
                            $powers=[$powers];
                        }
                        $funarray[strtolower($fun['uri'])]=$powers;
                    }

                }
            }
            $vars['functionlist']=json_encode($funarray);
            //dump($vars);exit;
            if(isset($id))
            {
                $svc->editRole($id,$vars);
            }else
            {
                $svc->addRole($vars);
            }

            $this->redirect('/system/Rolelist');
        }

        if(!isset($svc))
        {
            $svc=new SystemSvc();
        }
        $rows=$svc->getRoles();

        $functions=$svc->getFunctionMap_with_cache();
        //dump($functions);
        $response->functions=$functions;
        $response->rows=$rows;
        $this->layoutSmarty();
    }

    public function roleList($request,$response)
    {
        if(!isset($svc))
        {
            $svc=new SystemSvc();
        }
        $rows=$svc->getRoles();
        $response->stitle='角色管理';
        $response->rows=$rows;
        $this->layoutSmarty();
    }
    public function roledel($request,$response)
    {
        $id=$request->id;
        if($id)
        {
            $dao=new System_Role();
            $dao->delete($id);
            $this->renderString('ok');
        }else
        {
            $this->renderString('非法操作');
        }

    }
    public function rolelock($request)
    {
        $id=$request->id;
        if($id)
        {
            $dao=new System_Role();
            $dao->lock($id);
        }
        $this->renderString('ok');
    }

    public function managerlist($request,$response)
    {
        $svc=new SystemSvc();
        $rows=$svc->getManagers();
        $response->stitle='管理员管理';
        foreach ($rows as &$row)
        {
            $row['rolename']=$svc->getRole($row['roleid'])['name'];
        }
        $response->rows=$rows;
        $this->layoutSmarty();
    }
    public function manageradd($request,$response)
    {
        $response->stitle='新增管理员';

        if($request->id)
        {
            $response->stitle='编辑管理员信息';
            $id=$request->id;
            $svc=new SystemSvc();
            $item=$svc->getManager($id);
            if(!$item)
            {
                $this->redirect('/system/managerlist');
            }
            $response->user=$item;

        }
        if($this->isPost())
        {
            $vars=$request->vars;
            $svc=new SystemSvc();
            if(empty($vars['password']))
            {
                unset($vars['password']);
            }else{
                $vars['password']=md5($vars['password']);
            }
            dump($vars);
            if(isset($id))
            {
                $svc->editManager($id,$vars);
            }else
            {
                $svc->addManager($vars);
            }

            $this->redirect('/system/managerList');
        }

        if(!isset($svc))
        {
            $svc=new SystemSvc();
        }
        $rows=$svc->getRoles(1);
        $baserow=['id'=>0,'name'=>'无权限','description'=>'无权限'];
        array_unshift($rows,$baserow);
        $response->roles=$rows;
        $this->layoutSmarty();
    }
    public function managerdel($request,$response)
    {
        $id=$request->id;
        if($id)
        {
            $dao=new System_Manager();
            $dao->delete($id);
            $this->renderString('ok');
        }else
        {
            $this->renderString('非法操作');
        }

    }
    public function managerlock($request)
    {
        $id=$request->id;
        if($id)
        {
            $dao=new System_Manager();
            $dao->lock($id);
        }
        $this->renderString('ok');
    }
}