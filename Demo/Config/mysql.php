<?php
/**
* date：2017年3月2日
* author：zhaojin
* encoding：UTF-8
* file：mysql.php
* doc：mysql數據庫配置類，目前改善中
* 
* 
		'host'=>'10.0.90.10',
		'port'=>'3306',
		'database'=>'test',
		'username'=>'root',
		'password'=>'XXXXXXX',
		'charset'=>'utf-8',
		'collation'=>'',
		'prefix'=>'',
		'option'=>[],
		'weight'=>9
**/
$options=array
(
	'web1'=>
		[
			'driver'=>'mysql',
			'options'=>
				[
						[
							
							'host'=>'192.168.57.91',
							'port'=>'3307',
							'database'=>'i.yoka.com',
							'username'=>'yoka',
							'password'=>'yoka.com',
							'charset'=>'utf-8',
							'collation'=>'',
							'prefix'=>'',
							'option'=>[],
							'weight'=>9
						]
				]
				
		]
);
return $options;