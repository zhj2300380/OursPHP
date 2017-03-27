<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/19
 * Time: 9:31
 * Doc:
 */
$options=array
(
    /**
     * 配置节点名称=>配置信息
     */
    'default'=>
        [
            'pages'=> 9, //只能是单数
            'data_total'=> "<li class=\"disabled\"><span>共{datatotal}条,{pagetotal}页</span></li>", //条数页数
            'page_start' => '<ul class="pagination">',
            'page_end' => '</ul>',
            'tpl_pre' => '<li><a href="@url@p=@i@{anchor}">&laquo;</a></li>',
            'tpl_pre_disable' => '<li class="disabled"><span>&laquo;</span></li>',
            'tpl_next' => '<li><a href="@url@p=@i@{anchor}">&raquo;</a></li>',
            'tpl_next_disable' => '<li class="disabled"><span>&raquo;</span></li>',
            'tpl_item' => '<li><a href="@url@p=@i@{anchor}">@i@</a></li>',
            'tpl_item_current' => '<li class="active"><a>@i@</a></li>',
            'tpl_more' => '<li><span>...</span></li>'
        ]
);
return $options;