<?php

function ip_fliter($ip,$iprule){
   $ipruleregexp=str_replace('.*','ph',$iprule);
   $ipruleregexp=preg_quote($ipruleregexp,'/');
   $ipruleregexp=str_replace('ph','\.[0-9]{1,3}',$ipruleregexp);

   if(preg_match('/^'.$ipruleregexp.'$/',$ip)) return true;
   else return false;
}
$curr_ip=$_SERVER['REMOTE_ADDR'];
$white_list=array('36.110.59.50','203.81.28.194','111.160.234.12','127.0.0.1'); //白名单规则
$test_success=false;
foreach($white_list as $iprule){
   if(ip_fliter($curr_ip,$iprule)){
      $test_success=true;
      break;
   }
}
if(!$test_success) exit('IP not in white list || '.$curr_ip);

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_DEBUG',true);
define('APP_PATH','./Application/');

// define('COMMON_PATH', dirname(dirname(__FILE__)).'/Common/'); // 应用公共目录
$server_ip = require dirname(dirname(__FILE__)).'/ip.php';
define('SERVER_IP', 'http://'.$server_ip['img_server'].'/');
define('MONGO_SERVER', '121.42.157.21');

require './ThinkPHP/ThinkPHP.php';
