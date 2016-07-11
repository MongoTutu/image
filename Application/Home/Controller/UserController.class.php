<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {

	public function index(){
		if(cookie('img_id')){
			$this->redirect('Index/index');
		}else{
			$this->display();
		}
	}

	public function login(){
		$phone = I('phone');
		$password = I('password');

		if(!check_phone($phone)){
			$this->error('phone format error');
		}

		if(!check_password($password)){
			$this->error('Passwords must be 6-16');
		}

		$user['phone'] = $phone;
		$user['password'] = md5_password($password);

		$user_model = D('Common/User');
		$res = $user_model->where($user)->find();

		if($res){
			cookie('img_id',$res['_id'],8640000);
			cookie('username',$res['username'],8640000);
			$this->redirect('Index/index');
		}else{
			$this->error('login info error');
		}
	}

	public function logout(){
		cookie('img_id',null);
		cookie('username',null);
		$this->redirect('index');
	}

}
