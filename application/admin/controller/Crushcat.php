<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;

class Crushcat extends Controller
{
	public function add()
	{
       
       if($this->request->post('dosubmit')){

       	 	$data=$this->request->post();  
       	 	unset($data['dosubmit']);      
	        $re=Db::name('crushcat')->insert($data,true);
	        if($re){
	        	$this->success('添加成功','crushcat/lst');
	        }else{
	        	$this->error('添加失败!');
	        }
       }
       
       return $this->fetch();
	}


	public function del()
	{
		#dj
		$id=$this->request->param('id');
        $re=Db::name('crushcat')->delete($id);
        if($re){
        	$this->success('删除成功！','crushcat/lst');
        }else{
        	$this->error('删除失败！');
        }

		return $this->fetch();
	}


	public function lst()
	{
		$crushcat=Db::name('crushcat')->select();
		if(!$crushcat){
			return '取出的数据为空值';
		}
		$this->assign('crushcat',$crushcat);
		return $this->fetch();
	}

	public function update()
	{
		if($this->request->post('dosubmit')){
       	 	$data=$this->request->post(); 
       	 	unset($data['dosubmit']);       
	        $re=Db::name('crushcat')->update($data);
	        if($re){
	        	$this->success('修改成功！','crushcat/lst');
	        }else{
	        	$this->error('修改失败!');
	        }
       }
        $id=$this->request->param('id');
        $findOne=Db::name('crushcat')->where('id=:id',['id'=>$id])->find();
        $this->assign('findOne',$findOne);
		return $this->fetch();
	}


}