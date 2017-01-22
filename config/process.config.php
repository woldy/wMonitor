<?php
	return [
		'enable'=>true,		//开启监控
		'proc_list'=>[		
			'memcache'=>'service memcached start',
			'mysql'=>'service mysql restart',
			'nginx'=>'service nginx start'
		]
	];