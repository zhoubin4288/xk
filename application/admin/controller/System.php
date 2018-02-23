<?php

namespace app\system\controller;

use think\Db;
use app\system\model\System as SystemModel;
use app\system\controller\Common;
use think\Session;

class system extends Common
{
	
  public function add(){
    
		if($this->request->isAjax()){            
       	 	$data=$this->request->post();   
       	 	//处理接收的数据
       	 	$data['last_login_ip']=$this->request->ip();
       	 	$data['login_time']=time();
       	 	$data['create_time']=time();
       	 	$data['key']=$this->generate_key();
       	 	$data['password']=md5(md5($data['password']).$data['key']);
       	 	$data['repassword']=md5(md5($data['repassword']).$data['key']);
            $data['logins']=0;

            //验证接收的数据
       	 	$validate=validate('System');
            if(!$validate->scene('add')->check($data)){
                //$this->ajaxShow('201',$validate->getError());
                echo $validate->getError(); die();
            }
       	 	 
          unset($data['repassword']);
          
	        $re=Db::name('system')->insert($data,true);

	        if($re){
	           $this->ajaxShow('200','添加成功！');
	        }else{
	            $this->ajaxShow('201','添加失败！');
	            //echo $re->getError();
	        }
       }

       $role=Db::name('role')->select();
       $this->assign('role',$role);
       return $this->fetch();
	}
 
  public function del(){
    	$id=$this->request->param('id');
        $re=Db::name('system')->delete($id);
        if($re){
	           $this->ajaxShow('200','添加成功！');
	        }else{
	            $this->ajaxShow('201','添加失败！');
	            //echo $re->getError();
	      }
  }

  public function lst()
	{
  		$system=Db::name('system')->select();
  		$count=count($system);
  		$this->assign('count',$count);
  		$this->assign('system',$system);
  		return $this->fetch();
	}

	public function update()
	{
		if($this->request->isAjax()){
       	 	$data=$this->request->post(); 
       
            //处理接收的数据
       	 	$data['last_login_ip']=$this->request->ip();
       	 	$data['login_time']=time();
       	 	$data['create_time']=time();
       	 	$data['key']=$this->generate_key();
       	 	$data['password']=md5(md5($data['password']).$data['key']);
       	 	$data['repassword']=md5(md5($data['repassword']).$data['key']);
            $data['logins']=0;
            //验证接收的数据
       	 	$validate=Validate('system');
            if(!$validate->scene('add')->check($data)){
                $this->ajaxShow('201',$validate->getError());
            }
       	 	 

	        $re=Db::name('system')->update($data);
	        
          if($re){
	        	$this->success('修改成功！','system/lst');
	        }else{
	        	$this->error('修改失败!');
	        }

       }
        $id=$this->request->param('id');
        $role=Db::name('role')->select();       
        $findOne=Db::name('system')->where('id=:id',['id'=>$id])->find();
        $this->assign('role',$role);
        $this->assign('findOne',$findOne);
		    return $this->fetch();
	}

}