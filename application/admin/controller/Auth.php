<?php

namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Page;
use app\admin\model\Auth as AuthModel;
use app\admin\controller\Common;

class Auth extends Common
{
	public function add()
	{ 
      
       if($this->request->isAjax()){
       	 	$data=$this->request->post(); 

       	 	//$this->ajaxShow('2011','添加成功！',$data); 
            // print_r($data);
            //$result[]=$data['']            
            //验证操作没有很好性的进行处理
          
  
	        $re=Db::name('auth')->insert($data,true); 
	        if($re){
	           $this->ajaxShow('200','添加成功！');
	        }else{
	            $this->ajaxShow('201','添加失败！');
	        }
        
       }
                   
       $authcat=Db::name('authcat')->select();
       $this->assign('authcat',$authcat);
       $this->view->engine->layout(false);
       return $this->fetch();
	}


	public function del()
	{
		#dj
		$id=$this->request->param('id');
        $re=Db::name('auth')->delete($id);
        if($re){
        	 $this->ajaxShow('200','删除成功！');
        }else{
        	 $this->ajaxShow('201','删除失败！');
        }
	}


	public function lst()
	{
		/*$auth="";
		//获取总数
        $count=Db::name('auth')->count();
        $Page = new Page($count,4);		//实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('header','条记录');
		$Page->setConfig('prev','<img src="__STATIC__/image/prev.gif" border="0" title="上一页" />');
		$Page->setConfig('next','<img src="__STATIC__/image/next.gif" border="0" title="下一页" />');
		$Page->setConfig('first','<img src="__STATIC__/image/first.gif" border="0" title="第一页" />');
		$Page->setConfig('last','<img src="__STATIC__/image/last.gif" border="0" title="最后一页" />');
		$show = $Page->show();	*/		//分页显示输出
		$count=Db::name('auth')->count();
		$auth=Db::name('auth')->paginate();
        //Config::get('VAR_PAGE');
		//Config::get('VAR_URL_PARAMS');
        $page = $auth->render();
       // print_r($auth);
        //die();        
		//$page=$auth->render();
		$this->assign('count',$count);
		$this->assign('auth',$auth);
		$this->assign('page',$page);
		return $this->fetch();
	}

	public function update()
	{
		if($this->request->isAjax()){
       	 	$data=$this->request->post(); 
            //验证操作没有很好性的进行处理

	        $re=Db::name('auth')->update($data);	        
	        if($re){
        	    $this->ajaxShow('200','修改成功！');
	        }else{
	        	 $this->ajaxShow('201','修改失败！');
	        }
        }
        $id=$this->request->param('id');
        $authcat=Db::name('authcat')->select();       
        $findOne=Db::name('auth')->where('id=:id',['id'=>$id])->find();
        $this->assign('authcat',$authcat);
        $this->assign('findOne',$findOne);
		return $this->fetch();
	}
    
}