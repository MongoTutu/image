<?php
return array(
	'DB_TYPE'			=>	'mongo',
	'DB_HOST'			=>	'127.0.0.1',
	'DB_NAME'			=>	'kadmin',
	'DB_USER'			=>	'',
	'DB_PWD'			=>	'',
	'DB_PORT'			=>	'27017',
	'DB_CHARSET'		=>	'utf8',
	'DB_PREFIX' 		=>	'',


	'URL_CASE_INSENSITIVE'  =>  true,
	'URL_MODEL'          => '2',

	'URL_ROUTER_ON'   => true,
	'URL_ROUTE_RULES'=>array(
		
	),


	'LOG_RECORD' => true, // 开启日志记录
	'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,WARN,NOTICE', // 只记录EMERG ALERT CRIT ERR 错误
);