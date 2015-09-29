<?php

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_DEBUG',true);
define('APP_PATH','./Application/');

// define('COMMON_PATH', dirname(dirname(__FILE__)).'/Common/'); // 应用公共目录
$server_ip = require dirname(dirname(__FILE__)).'/ip.php';
define('SERVER_IP', 'http://'.$server_ip['img_server'].'/');
define('MONGO_SERVER', $server_ip['db_server']);

require './ThinkPHP/ThinkPHP.php';
