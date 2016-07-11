<?php
function multi_explode($delimiters,$string){
	$str = str_replace($delimiters, '|', $string);
	$arr = explode('|', $str);
	$arr = array_map('trim', $arr); // 去空格
	$arr = array_filter($arr); //过滤为空项
	return $arr;
}

function get_token($str='',$skey='justAKey'){
	$strArr = str_split(base64_encode($str));
	$strCount = count($strArr);
	foreach(str_split($skey) as $k=>$v){
		$k < $strCount && $strArr[$k] .= $v;
	}
	return str_replace(array('=','+','/'),array('O0O0O','o000o','oo00o'),join('',$strArr));
}

function check_token($token='',$skey='justAKey'){
	$strArr = str_split(str_replace(array('O0O0O','o000o','oo00o'), array('=','+','/'), $token),2);
	$strCount = count($strArr);
	foreach(str_split($skey) as $k=>$v){
		$k <= $strCount && $strArr[$k][1] === $v && $strArr[$k] = $strArr[$k][0];
	}
	return base64_decode(join('',$strArr));
}

function check_email($email){
	$email = strtolower($email);
	$reg = '/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/';
	$res = preg_match($reg,$email);
	return $res;
}

function check_phone($phone){
	$reg = '/^1[34578][0-9]{9}$/';
	$res = preg_match($reg,$phone);
	return $res;
}

function check_password($password){
	// $reg = '/(?!^\\d+$)(?!^[a-zA-Z]+$).{6,16}/';
	// $res = preg_match($reg,$password);
	$res = isset($password[5]);
	return $res;
}

function md5_password($password){
	return md5('KStart'.$password);
}

function check_username($username){
	// $reg = '/^[0-9a-zA-Z_\x{4e00}-\x{9fa5}]{2,16}$/u';
	// $res = preg_match($reg,$username);
	$res = isset($username);
	return $res;
}

function http_status($ds){
	$code = '';
	$message = '';
	switch ($ds) {
		case 200:
			$code = 200;
			$message = 'ok';
			break;
		case 201:
			$code = 201; //用户新建或修改数据成功
			$message = 'created';
			break;
		case 204:
			$code = 204; //用户删除数据成功
			$message = 'no content';
		case 304:
			$code = 304; //HTTP缓存有效
			$message = 'not modified';
			break;
		case 400: //用户发出的请求有错误，服务器没有进行新建或者修改数据的操作
			$code = 400;
			$message = 'bad request';
			break;
		case 401: //未授权
			$code = 401;
			$message = 'unauthorized';
			break;
		case 403: //鉴定权限成功，但是该用户没有权限
			$code = 403;
			$message = 'forbidden';
			break;
		case 404:
			$code = 404;
			$message = 'not found';
			break;
		case 405:
			$code = 405;
			$message = 'method not allowed';
			break;
		case 410:
			$code = 410;
			$message = 'gone';
			break;
		case 415: //请求类型错误
			$code = 415;
			$message = 'unsupported media type';
			break;
		case 422:
			$code = 422;
			$message = 'unprocessable entity';
			break;
		case 429: //请求过多
			$code = 429;
			$message = 'too many request';
			break;
		default:
			$code = 500; // 服务器发生错误，用户将无法判断发出的请求是否成功
			$message = 'internal server error';
			break;
	}
	return array('code'=>$code,'message'=>$message);
}

function trs_tags($tags,$w_tags){
	$tag = multi_explode(array(',','，'),$w_tags);
	$stag = $tags;
	if(!is_array($stag)){$stag = array();}
	$tags = array_unique(array_merge($tag,$stag));
	return $tags;
}

function trs_info($info){
	if(!$info){
		return '<i style="color:red">未完善</i>';
	}
	return '<i style="color:green">已完善</i>';
}

function art_status($status){
	if($status==1){
		return '<i style="color:red">待审核</i>';
	}
	return '<i style="color:green">已通过</i>';
}

function trs_invitation($status){
	if($status==1){
		return '<i style="color:red">未使用</i>';
	}
	return '<i style="color:green">已使用</i>';
}

function questionnaire_classify($classify){
	switch ($classify) {
		case 1:
			$str = '企业调查问卷';
			break;
		case 2:
			$str = '企业调查问卷（英）';
			break;
		default:
			$str = '企业调查问卷';
			break;
	}
	return $str;
}

function getusername($id){
	$username = D('Common/User')->where(array('_id'=>$id))->getfield('username');
	return $username ? $username : 'Null';
}

function getuserphone($id){
	$username = D('Common/User')->where(array('_id'=>$id))->getfield('phone');
	return $username ? $username : 'Null';
}

function cmp($a,$b){
	if ($a["time"] == $b["time"]){
		return 0;
	}
	return ($a["time"] > $b["time"]) ? 1 : -1;
}

function score_cmp($a,$b){
	if($a == $b){
		return 0;
	}
	return $a > $b ? -1 : 1;
}

function score_cmp2($a,$b){
	if($a == $b){
		return 1;
	}
	return $a > $b ? -1 : 1;
}

function trs_role($role){
	switch ($role) {
		case '创业者':
			return 'entrepreneur';
			break;
		case '投资人':
			return 'investor';
			break;
		case '行业专家':
			return 'expert';
			break;
		case '孵化器':
			return 'incubator';
			break;
		case '园区':
			return 'incubator';
			break;
		case '领军企业':
			return 'investor';
			break;
		default:
			return 'entrepreneur';
			break;
	}
}

function trs_type($type){
	switch ($type) {
		case 'cooperation':
			return '合作';
			break;
		case 'funding':
			return '资金';
			break;
		case 'authorize':
			return '认证';
			break;
		case 'expert':
			return '专家';
			break;
		default:
			return '专家';
			break;
	}
}

function get_rounds($r){
	$rounds = array(
		0 => '种子期 ( 种子轮 )',
		1 => '初创期 ( 天使轮 )',
		2 => '成长期 ( A轮 )',
		3 => '扩张期 ( B轮 )',
		4 => '成熟期 ( C轮 )',
		5 => 'Pre-IPO ( D轮 )'
	);
	return $rounds[$r] ? $rounds[$r] : $r;
}

function get_industry($in,$sub){
	$industry = array(
		0 => array(
			'industry'=>'医疗保健',
			'sub_industry' =>array('医疗保健'),
		),
		1 => array(
			'industry' => '生物科技',
			'sub_industry' => array('生物科技')
		),
		2 => array(
			'industry' => '非必需消费品',
			'sub_industry' => array('汽车及零部件','耐用消费品与服装','酒店餐馆与休闲','传媒','零售')
		),
		3 => array(
			'industry' => '商品',
			'sub_industry' => array('食品与主要用品零售','食品','家庭与个人用品')
		),
		4 => array(
			'industry' => '金融',
			'sub_industry' => array('银行','多元金融','保险','房地产')
		),
		5 => array(
			'industry' => '电信服务',
			'sub_industry' => array('电信服务')
		),
		6 => array(
			'industry' => '商业及专业服务',
			'sub_industry' => array('商业及专业服务')
		),
		7 => array(
			'industry' => '清洁技术',
			'sub_industry' => array('农业科技','能源','环境','材料','水处理技术')
		),
		8 => array(
			'industry' => '通信',
			'sub_industry' => array('宽带接入','广播','企业网络','家庭网络','移动应用','下一代网络与融合','光网络','电信应用','网络电话和IP电话','无线应用','无线基础设施')
		),
		9 => array(
			'industry' => '互联网',
			'sub_industry' => array('网络广告','互联网应用','内容管理','内容发布平台','电子商务','远程教育','在线娱乐','网络基础架构','搜索引擎','社交网络')
		),
		10 => array(
			'industry' => 'IT和企业软件',
			'sub_industry' => array('业务分析','设计和开发工具','企业应用','企业基础设施','其他软件','安全','信息安全／网络')
		),
		11 => array(
			'industry' => '生命科学',
			'sub_industry' => array('农业生物','生物信息学','生物制品','诊断','医疗保健IT','工业','医疗设备','远程医疗','治疗')
		),
		12 => array(
			'industry' => '半导体',
			'sub_industry' => array('无线通信','有线通信和家庭网络','网络处理器','视频和图像处理器','制造设备及EDA','制造和测试','内存','处理器和RFID','安全半导体','杂项半导体')
		),
		13 => array(
			'industry' => '杂项技术',
			'sub_industry' => array('防御','硬件','工业技术','纳米技术','杂项')
		),
		14 => array(
			'industry' => '公用事业',
			'sub_industry' => array('公用事业')
		),
		15 => array(
			'industry' => '其他',
			'sub_industry' => array('其他')
		)
	);
	if(isset($sub)){
		return $industry[$in]['sub_industry'];
	}
	return $industry[$in]['industry'];
}

function get_sub_industry($in,$sub){
	$sub_industry = get_industry($in,$sub);
	return $sub_industry[$sub];
}
