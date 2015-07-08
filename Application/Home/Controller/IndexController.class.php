<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function index(){
		$data = D('Common/Images')->select();
		$this->data = $data;
		$this->display();
	}

	public function add(){
		$this->display();
	}

	public function upload_images(){
		$t = I('tags');
		if(!$t){
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
			$img = $info['image'];
			
			$tag = explode(',', $t);
			if(!is_array($t)){$t = array();}
			$t = array_unique(array_merge($tag,$t));
			
			$img['tags'] = $t;			
			$res['path'] = $img['savepath'].$img['savename'];
			
			$image = new \Think\Image(); 
			$image->open($config['rootPath'].$res['path']);
			$image->thumb(375,1000000)->save($config['rootPath'].$img['savepath'].'s_'.$img['savename']);
			
			$img['thumb'] = 's_'.$img['savename'];
			$img['time'] = time();
			$img['dir'] = 'images/';
			$servername = 'http://images.zhangtutu.club';
			$img['servername'] = $servername;
			$img['img_url'] = $servername.'/'.$res['path'];
			$img['thumb_url'] = $servername.'/'.$img['savepath'].'s_'.$img['savename'];
			
			$images = D('Common/Images');
			$rs = $images->data($img)->add();
			if($rs){
				$this->success('Success');
			}
		}
	}


}