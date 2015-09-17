<?php
$img_server = '127.0.0.1';
// $img_server = '58.32.236.76';
// $img_server = '121.42.157.21';
// $img_server = '203.81.28.218';
$mongo_server = '127.0.0.1';
// $mongo_server = '10.166.210.7';

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_DEBUG',true);

define('SERVER_IP', 'http://'.$img_server.'/');
define('MONGO_SERVER', $mongo_server);

define('APP_PATH','./Application/');

require './ThinkPHP/ThinkPHP.php';
