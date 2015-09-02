<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	
	public function index(){
		$tag = I('tag');
		$p = I('p') ? intval(I('p')) : 1;
		$limit = 30;
		if($tag){
			$data = D('Common/Images')->where(array('tags'=>$tag))->order('time desc')->page($p.','.$limit)->select();
			$count = D('Common/Images')->where(array('tags'=>$tag))->count();
		}else{
			$data = D('Common/Images')->order('time desc')->page($p.','.$limit)->select();
			$count = D('Common/Images')->count();
		}
		$Page = new \Think\Page($count,$limit);
		$show = $Page->show();
		$this->page = $show;
		$this->data = $data;
		$this->tags = $this->gettags();
		$this->display();
	}

	public function image(){
		$data = D('Common/Images')->order('time desc')->where(array('sc'=>1))->select();
		$this->data = $data;
		$this->tags = $this->gettags();
		$this->display();
	}

	public function change_ip(){
		die;
		$servername = 'http://121.42.157.21/';
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
			$t = array_merge($t,$value['tags']);
		}
		$t = array_unique($t);
		return $t;
	}

	public function add(){
		$this->tags = $this->gettags();
		$this->display();
	}

	public function add_2(){
		$this->tags = $this->gettags();
		$this->display();
	}

	public function upload_images(){
		$t = I('tags');
		$classify = I('classify');
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
			$servername = 'http://121.42.157.21/images/';
			$img['servername'] = $servername;
			$img['img_url'] = $servername.$res['path'];
			$img['thumb_url'] = $servername.$img['savepath'].'s_'.$img['savename'];
			
			$images = D('Common/Images');
			$rs = $images->data($img)->add();
			if($rs){
				$this->success('Success');
			}
		}
	}

	public function upload_images_2(){
		$classify = I('classify');
		if(!$classify){
			header('HTTP/1.1 404 NOT FOUND');
			return false;
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
			$img = $info['Filedata'];
			$img['sc'] = 1;
			$img['classify'] = $classify;
			
			$res['path'] = $img['savepath'].$img['savename'];
			
			$image = new \Think\Image(); 
			$image->open($config['rootPath'].$res['path']);
			$image->thumb(375,1000000)->save($config['rootPath'].$img['savepath'].'s_'.$img['savename']);
			
			$img['thumb'] = 's_'.$img['savename'];
			$img['time'] = time();
			$img['dir'] = 'images/';
			$servername = 'http://121.42.157.21/images/';
			$img['servername'] = $servername;
			$img['img_url'] = $servername.$res['path'];
			$img['thumb_url'] = $servername.$img['savepath'].'s_'.$img['savename'];
			
			$images = D('Common/Images');
			$rs = $images->data($img)->add();
		}
	}


}