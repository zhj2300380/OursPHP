<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/4/7
 * Time: 10:47
 * Doc: withcache配置项
 */
$options=array
(
    'manage'=>
        [
            'used'=>true,
            'type'=>'redis',
            'nodename'=>'default',
            'cachetime'=>600
        ]
);
return $options;