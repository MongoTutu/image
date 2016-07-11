<?php
namespace Common\Model;
use Think\Model\MongoModel;
class UserModel extends MongoModel{

	public function get_user_token($id){
		$d = $this->where(array('_id'=>$id))->find();
		$token = $id.':'.$d['username'];
		$tk = get_token($token);
		$rep = $d['phone'].':'.$tk;
		return array('id'=>$d['_id'],'token'=>$rep);
	}

	public function user_register($user=array()){
		$res = $this->data($user)->add();
		D('Common/Sysmessage')->send_sys_message($res,'恭喜您注册成功！欢迎使用毕业加!!!');
		return $this->get_user_token($res);
	}

	public function is_vip($id){
		$res = $this->where(array('_id'=>$id,'vip'=>1))->find();
		if($res){
			$ret['is_vip'] = 1;
			$role = D('Common/Useranalysis')->where(array('_id'=>$id))->getfield('classify');
			$ret['role'] = trs_role($role);
		}else{
			$ret['is_vip'] = 0;
			$ret['role'] = null;
		}
		return $ret;
	}

	public function user_login($user=array()){
		$res = $this->where($user)->find();
		if($res){
			$u = $this->get_user_token($res['_id']);
			$ret = $this->is_vip($res['_id']);
			$u['is_vip'] = $ret['is_vip'];
			$u['role'] = $ret['role'];
			D('Common/Signlog')->logs($res['_id']);
			return $u;
		}else{
			$res['meta'] = http_status(401);
			$res['data'] = array('status'=>0,'tips'=>'(Login information is incorrect, you can not log in)登录信息错误，无法登录');
			return $res;
		}
	}

	public function is_email_exsist($email){
		$res = $this->where(array('email'=>$email))->find();
		return $res;
	}

	public function is_phone_exsist($phone){
		$res = $this->where(array('phone'=>$phone))->find();
		return $res;
	}

	public function token_to_id($token){
		$getk = explode(':', $token);
		$token_phone = $getk[0];
		$token_keys = $getk[1];
		$ckt = check_token($getk[1]);

		$geten = explode(':', $ckt);
		$id = $geten[0];
		$username = $geten[1];

		$res = $this->where(array('_id'=>$id,'phone'=>$token_phone))->find();
		if($res){
			return $id;
		}else{
			return false;
		}
	}
}
