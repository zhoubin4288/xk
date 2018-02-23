<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\admin\controller\Common;

class imagescat extends Common
{
	public function add()
	{
       
       if($this->request->isAjax()){

       	 	$data=$this->request->post();  
       	 	//验证操作

	        $re=Db::name('imagescat')->insert($data,true);
	        if($re){
	        	$this->ajaxShow('200','修改成功！');
		    }else{
		        $this->ajaxShow('201','修改失败！');
		    }
       }
       
       return $this->fetch();
	}


	public function del()
	{
		#dj
		$id=$this->request->param('id');
        $re=Db::name('imagescat')->delete($id);
        if($re){
        	$this->ajaxShow('200','修改成功！');
	    }else{
	        $this->ajaxShow('201','修改失败！');
	     }
	}


	public function lst()
	{
		$imagescat=Db::name('imagescat')->paginate(20);
		$page=$imagescat->render();
		$count=count($imagescat);
		$this->assign('count',$count);
		$this->assign('imagescat',$imagescat);
		$this->assign('page',$page);
		return $this->fetch();
	}

	public function update()
	{
		if($this->request->isAjax()){
       	 	$data=$this->request->post();

       	 	      
	        $re=Db::name('imagescat')->update($data);
	        if($re){
        	    $this->ajaxShow('200','修改成功！');
	        }else{
	        	 $this->ajaxShow('201','修改失败！');
	        }
        }
        $id=$this->request->param('id');
        $findOne=Db::name('imagescat')->where('id=:id',['id'=>$id])->find();
        $this->assign('findOne',$findOne);
		return $this->fetch();
	}

}
