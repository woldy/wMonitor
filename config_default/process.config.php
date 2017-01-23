<?php
	return [
		'enable'=>false,		//开启监控
		'retry'=>'3',		//重试次数
		'sleep'=>2,
		'proc_list'=>[		
			'memcache'=>'service memcached start',
			'mysql'=>'service mysql restart',
			'nginx'=>'service nginx start'
		]
	];