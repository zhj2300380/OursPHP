<?php
/**
 * Created by PhpStorm.
 * User: Jack Zhao
 * Date: 2017/3/4
 * Time: 19:00
 * doc：redis 缓存配置文件
* 項目中可使用多組不同的redis集群衹需要從注冊器中獲取即 * Demo:可
 * Demo:
$options=array
(
  'default'=>
         [
             '10.0.90.10',
             6379,
             'redispasswd',
             1
         ],
     'web1'=>
         [
             '10.0.90.10',
             6379,
             'redispasswd',
             1
         ],
);
return $options;
 */
$options=array
(
    'default'=>
        [
            '10.0.90.10',
            6379,
            'redispasswd',
            1
        ],
    'web1'=>
        [
            '10.0.90.10',
            6379,
            'redispasswd',
            1
        ],
);
return $options;