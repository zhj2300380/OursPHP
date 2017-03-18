<?php
/**
* date：2017年3月4日
* author：zhaojin
* encoding：UTF-8
* file：memcached.php
* doc：memcached 缓存配置文件
項目中可使用多組不同的memcached集群衹需要從注冊器中獲取即可
* Demo:
$options=array
(
    'default'=>// memcached集群1
        [
            ['10.0.90.10', '11211']
        ],
    'web1'=>// memcached集群2
        [
            ['10.0.90.10', '11211']
        ],
);
return $options;
**/
$options=array
(
    'default'=>
        [
            ['10.0.90.10', '11211']
        ],
    'web1'=>
        [
            ['10.0.90.10', '11211']
        ],
);
return $options;