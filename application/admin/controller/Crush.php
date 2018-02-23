<?php

namespace app\admin\controller;
use think\Db;
use think\Controller;

class Crush extends Controller
{
	public function add()
	{
       
       if($this->request->post('dosubmit')){

       	 	$data=$this->request->post();  
       	 	unset($data['dosubmit']);      
	        $re=Db::name('crush')->insert($data,true);
	        if($re){
	        	$this->success('添加成功','crush/lst');
	        }else{
	        	$this->error('添加失败!');
	        }
       }
       $crushcat=Db::name('crushcat')->select();
       $this->assign('crushcat',$crushcat);
       return $this->fetch();
	}


	public function del()
	{
		#dj
		$id=$this->request->param('id');
        $re=Db::name('crush')->delete($id);
        if($re){
        	$this->success('删除成功！','crush/lst');
        }else{
        	$this->error('删除失败！');
        }

		return $this->fetch();
	}


	public function lst()
	{
		$crush=Db::name('crush')->select();
		if(!$crush){
			return '取出的数据为空值';
		}
		$this->assign('crush',$crush);
		return $this->fetch();
	}

	public function update()
	{
		if($this->request->post('dosubmit')){
       	 	$data=$this->request->post(); 
       	 	unset($data['dosubmit']);       
	        $re=Db::name('crush')->update($data);
	        if($re){
	        	$this->success('修改成功！','crush/lst');
	        }else{
	        	$this->error('修改失败!');
	        }
        }
        
        $id=$this->request->param('id');
        $crushcat=Db::name('crushcat')->select();       
        $findOne=Db::name('crush')->where('id=:id',['id'=>$id])->find();
        $this->assign('crushcat',$crushcat);
        $this->assign('findOne',$findOne);
		return $this->fetch();
	}
	
}