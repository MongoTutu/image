<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

	public function _initialize(){
		$cookieid = cookie('img_id');
		if(!$cookieid){
			$this->redirect('User/index');
		}
		$ex = D('Common/User')->where(array('_id'=>$cookieid,'username'=>cookie('username')))->find();
		if(!$ex){
			$this->redirect('User/logout');
		}
	}

	public function index(){
		$tag = I('tag');
		$p = I('p') ? intval(I('p')) : 1;
		$limit = 30;
		if($tag){
			$data = D('Common/Images')->where(array('tags'=>$tag))->order('time desc')->page($p.','.$limit)->select();
			$count = D('Common/Images')->where(array('tags'=>$tag))->count();
		}else{
			$data = D('Common/Images')->order('time desc')->where(array('sc'=>array('NEQ',1)))->page($p.','.$limit)->select();
			$count = D('Common/Images')->where(array('sc'=>array('NEQ',1)))->count();
		}
		$Page = new \Think\Page($count,$limit);
		$show = $Page->show();
		$this->page = $show;
		$this->data = $data;
		$this->tags = $this->gettags();
		$this->display();
	}

	public function edit(){
		$id = I('id');
		$this->data = D('Common/Images')->where(array('_id'=>$id))->find();
		$this->tags = $this->gettags();
		$this->display();
	}

	public function edit_images(){
		$id = I('id');
		$tag = I('tags');
		$tags = multi_explode(array(',','，'),$tag);
		$classify = I('classify');
		if($classify){
			$data['classify'] = $classify;
			$data['sc'] = 1;
		}else{
			$data['sc'] = 0;
		}
		$data['tags'] = $tags;
		$res = D('Common/Images')->where(array('_id'=>$id))->data($data)->save();
		if($res){
			$this->success('Success');
		}else{
			$this->error('Error');
		}
	}

	public function image(){
		$p = I('p') ? intval(I('p')) : 1;
		$limit = 30;
		$data = D('Common/Images')->order('time desc')->where(array('sc'=>1))->page($p.','.$limit)->select();
		$count = D('Common/Images')->where(array('sc'=>1))->count();
		$Page = new \Think\Page($count,$limit);
		$show = $Page->show();
		$this->page = $show;
		$this->data = $data;
		$this->tags = $this->gettags();
		$this->display();
	}

	public function change_ip(){
		die;
		$servername = SERVER_IP;
		$data = D('Common/Images')->select();
		foreach($data as $k=>$v){
			$data[$k]['img_url'] = $servername.$v['dir'].$v['savepath'].$v['savename'];
			$data[$k]['thumb_url'] = $servername.$v['dir'].$v['savepath'].'s_'.$v['savename'];
			$data[$k]['servername'] = $servername;
			D('Common/Images')->where(array('_id'=>$v['_id']))->data($data[$k])->save();
		}
		dump($data);
	}


	public function gettags(){
		$tags = D('Common/Images')->field('tags')->select();
		$t = array();
		foreach ($tags as $key => $value) {
			if($value['tags']){
				$t = array_merge($t,$value['tags']);
			}
		}
		$ts = array();
		foreach($t as $k=>$v){
			$ts[$v] ++;
		}
		arsort($ts);
		$ts = array_keys($ts);
		$t = array();
		for($i=0;$i<40;$i++){
			if(!$ts[$i]){
				continue;
			}
			$t[] = $ts[$i];
		}
		$t = array_unique($t);
		return $t;
	}

	public function add(){
		$this->tags = $this->gettags();
		$this->display();
	}

	public function upload_images(){
		$t = I('tags');
		$classify = I('classify');
		if(!$t && !$classify){
			$this->error('没有标签');
		}

		$config = array(
			'maxSize'    =>    3145728,
			'rootPath'   =>    '../images/',
			'savePath'   =>    '',
			'saveName'   =>    array('uniqid',''),
			'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
			'autoSub'    =>    true,
			'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);
		$info   =   $upload->upload();
		if(!$info) {
			$this->error($upload->getError());
		}else{
			$tag = multi_explode(array(',','，'),$t);

			foreach($info as $k=>$v){
				$img = $v;

				$img['tags'] = $tag;
				if($classify){
					$img['sc'] = 1;
					$img['classify'] = $classify;
				}

				$res['path'] = $img['savepath'].$img['savename'];
				$image = new \Think\Image();
				$image->open($config['rootPath'].$res['path']);
				$image->thumb(375,1000000)->save($config['rootPath'].$img['savepath'].'s_'.$img['savename']);

				$img['thumb'] = 's_'.$img['savename'];
				$img['time'] = time();
				$img['dir'] = 'images/';
				$servername = SERVER_IP.'images/';
				$img['servername'] = $servername;
				$img['img_url'] = $servername.$res['path'];
				$img['thumb_url'] = $servername.$img['savepath'].'s_'.$img['savename'];

				$images = D('Common/Images');
				$rs = $images->data($img)->add();
			}
			if($rs){
				$this->success('Success');
			}
		}
	}


}
