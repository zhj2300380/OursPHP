<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/12
 * Time: 15:23
 * Doc:上传配置类
 */
$options=array
(
    /**
     * 站点命名空间=>配置信息
     */
    'manage'=>
        [
            'size'=>10000,
            'savepath'=>'upload',
            'viewpath'=>'http://www.yoka.com/upload',
            'type'=>
                [
                    'gif'=>'image/gif',
                    'jpg'=>'image/jpeg',
                    'jpeg'=>'image/jpeg',
                    'png'=>'image/png',
                    'zip'=>'application/x-zip-compressed',
                    'rar'=>'application/octet-stream',
                    'pdf'=>'application/pdf',
                    'xls'=>'application/vnd.ms-excel',
                    'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'doc'=>'application/msword',
                    'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'ppt'=>'application/vnd.ms-powerpoint',
                    'pptx'=>'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                ]
        ],
    'web'=>'.htm',
    'api'=>''
);
return $options;