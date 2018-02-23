<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\admin\controller\Common;

class Authcat extends Common
{
	public function add()
	{
       
       if($this->request->isAjax()){

       	 	$data=$this->request->post();  
       	 	//验证操作
            
	        $re=Db::name('authcat')->insert($data,true);
	        if($re){
	        	$this->ajaxShow('200','修改成功！');
		    }else{
		        $this->ajaxShow('201','修改失败！');
		    }
       }
       $module=Db::name('module')->select();
       $this->assign('module',$module);
       return $this->fetch();
	}


	public function del()
	{
		#dj
		$id=$this->request->param('id');
        $re=Db::name('authcat')->delete($id);
        if($re){
        	$this->ajaxShow('200','修改成功！');
	    }else{
	        $this->ajaxShow('201','修改失败！');
	     }
	}


	public function lst()
	{
		$authcat=Db::name('authcat')->paginate(20);
		$page=$authcat->render();
		$count=count($authcat);
		$this->assign('count',$count);
		$this->assign('authcat',$authcat);
		$this->assign('page',$page);
		return $this->fetch();
	}

	public function update()
	{
		if($this->request->isAjax()){
       	 	$data=$this->request->post();

       	 	      
	        $re=Db::name('authcat')->update($data);
	        if($re){
        	    $this->ajaxShow('200','修改成功！');
	        }else{
	        	 $this->ajaxShow('201','修改失败！');
	        }
        }
        $module=Db::name('module')->select();       
        $id=$this->request->param('id');
        $findOne=Db::name('authcat')->where('id=:id',['id'=>$id])->find();
        $this->assign('module',$module);
        $this->assign('findOne',$findOne);
		return $this->fetch();
	}

}