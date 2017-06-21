<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/19
 * Time: 8:32
 * Doc:
 */
namespace OursPHP\Core\Lib\Pagination;

use OursPHP\Init\ConfigManage;

class PageLinkManage
{
    protected $_pages = 5; //只能是单数
    protected $_data_total;
    protected $_page_start;
    protected $_page_end;
    protected $_tpl_pre ;
    protected $_tpl_pre_disable ;
    protected $_tpl_next;
    protected $_tpl_next_disable;
    protected $_tpl_item ;
    protected $_tpl_item_current;
    protected $_tpl_more;
    private $_anchor = '';

    private static $_pagelinklist;
    public function __construct(array $options)
    {
        $this->_pages=isset($options['pages'])?$options['pages']:$this->_pages;
        $this->_data_total=isset($options['data_total'])?$options['data_total']:$this->_data_total;
        $this->_page_start=$options['page_start'];
        $this->_tpl_pre=$options['tpl_pre'];
        $this->_tpl_pre_disable=$options['tpl_pre_disable'];
        $this->_tpl_next=$options['tpl_next'];
        $this->_tpl_next_disable=$options['tpl_next_disable'];
        $this->_tpl_item=$options['tpl_item'];
        $this->_tpl_item_current=$options['tpl_item_current'];
        $this->_tpl_more=$options['tpl_more'];
    }

    public static function  getInstance($nodeName='default') {
        if(!isset(self::$_pagelinklist[$nodeName]))
        {
            $options=ConfigManage::getConfig('pagination',$nodeName);
            $_pagelink = new self($options);
            self::$_pagelinklist[$nodeName]=$_pagelink;
        }
        return self::$_pagelinklist[$nodeName];
    }
    /**
     * 生成分页模版html
     * @param string $url 分页基本url
     * @param int $pid 当前页码
     * @param int $pageSize 每页条数
     * @param int $total 数据总条数
     * @param string $anchor 锚点
     * @return string 输出的分页条字符串
     */
    public function getPageLink($url, $pid, $pageSize=20, $total, $anchor='') {
        $this->_anchor = $anchor;

        $this->_tpl_pre=str_replace('{anchor}', $this->_anchor, $this->_tpl_pre);
        $this->_tpl_next=str_replace('{anchor}', $this->_anchor, $this->_tpl_next);
        $this->_tpl_item=str_replace('{anchor}', $this->_anchor, $this->_tpl_item);

        $url = (strpos($url, '?')>=0)? $url.'&':$url.'?';
        $pages = ceil($total/$pageSize);
        if ($pid<1)
            $pid = 1;
        if ($pid>$pages)
            $pid = $pages;

        $relPages = ($pages<$this->_pages)? $pages:$this->_pages;
        if ($relPages<=1)
            return '';
        $this->_data_total=str_replace('{datatotal}', $total, $this->_data_total);
        $this->_data_total=str_replace('{pagetotal}', $relPages, $this->_data_total);
        $harf = ceil($this->_pages-1)/2;
        $p2 = $pid-$harf-1;
        $p3 = $pages-$this->_pages;
        for ($i=2; $i<$this->_pages; $i++) {
            $n1[$i] = $i;
            $n2[$i] = $p2+$i;
            $n3[$i] = $p3+$i;
        }

        if (in_array($pid+2, $n1))
            $n = $n1;
        else if (in_array($pid-2, $n3))
            $n = $n3;
        else
            $n = $n2;


        if ($n[2]!=2)
            $n[2] = $this->_tpl_more;

        if (isset($n[$relPages-1]) && $n[$relPages-1] != $pages-1)
            $n[$relPages-1] = $this->_tpl_more;

        $n[0] = ($pid==1)? $this->_tpl_pre_disable:str_replace('@i@', $pid-1, $this->_tpl_pre);
        $n[1] = 1;
        $n[$relPages] = $pages;
        $n[$relPages+1] = ($pid==$pages)? $this->_tpl_next_disable:str_replace('@i@', $pid+1, $this->_tpl_next);

        for($i=0; $i<=$relPages+1; $i++) {
            $items[$i] = $n[$i];

            if (!is_string($items[$i])) {
                if ($pid==$n[$i]) {
                    $items[$i] = str_replace('@i@', $items[$i], $this->_tpl_item_current);
                } else {
                    $items[$i] = str_replace('@i@', $items[$i], $this->_tpl_item);
                }
            }
            $items[$i] = str_replace('@url@', $url, $items[$i]);

        }

        return $this->_page_start.implode('', $items).$this->_data_total.$this->_page_end;
    }

    /**
     * 设置fan用的模版
     */
    public function setFanTpl() {
        $this->pages = 9; //只能是单数
        $this->page_start = '<div class="pagebox"><div>';
        $this->page_end = '</div></div>';
        $this->tpl_pre = '<a hasusercardevent="1" class="prepage" href="@url@p=@i@">上一页</a><i></i>';
        $this->tpl_pre_disable = '<span class="prepage page_disable"></span><i></i>';
        $this->tpl_next = '<i></i><a hasusercardevent="1" class="nextpage" href="@url@p=@i@">下一页</a>';
        $this->tpl_next_disable = '<i></i><span class="nextpage page_disable"></span>';
        $this->tpl_item = '<a hasusercardevent="1" href="@url@p=@i@">@i@</a>';
        $this->tpl_item_current = '<span class="pager_cur">@i@</span>';
        $this->tpl_more = '<i>...</i>';
    }
    /**
     *设置twitter bootstrap 用的模板
     */
    public function setTwitterTpl() {
        $anchor = $this->_anchor? '#'.$this->_anchor:'';
        $this->pages = 9; //只能是单数
        $this->page_start = '<ul class="pagination">';
        $this->page_end = '</ul>';
        $this->tpl_pre = '<li><a href="@url@p=@i@'.$anchor.'">&laquo;</a></li>';
        $this->tpl_pre_disable = '<li class="disabled"><span>&laquo;</span></li>';
        $this->tpl_next = '<li><a href="@url@p=@i@'.$anchor.'">&raquo;</a></li>';
        $this->tpl_next_disable = '<li class="disabled"><span>&raquo;</span></li>';
        $this->tpl_item = '<li><a href="@url@p=@i@'.$anchor.'">@i@</a></li>';
        $this->tpl_item_current = '<li class="active"><a>@i@</a></li>';
        $this->tpl_more = '<li><span>...</span></li>';
    }
}