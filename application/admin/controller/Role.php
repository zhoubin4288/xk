<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Auth as AuthModel;
use think\Db;
use app\admin\controller\Common;

class Role extends Common
{
	public function add()
	{
       
       if($this->request->isAjax()){

       	 	$data=$this->request->post(); 
            $data['role_auth']=substr($data['role_auth'],0,strlen($data['role_auth'])-1); 
       	 	//$data['role_auth']=$this->arrtostr($data['role_auth']);
            //对数据而进行验证操作；
	        $re=Db::name('role')->insert($data,true);
	        if($re){
	           $this->ajaxShow('200','添加成功！');
	        }else{
	            $this->ajaxShow('201','添加失败！');
	        }
       }

       //获取全部的权限
       $authcat=Db::name('authcat')->select();
       $auth= Db::name('auth')->select();
       $this->assign([
       	   'authcat'=>$authcat,
       	   'auth'=>$auth
       ]);
       return $this->fetch();
	}


	public function del()
	{
		#dj
		$id=$this->request->param('id');

		//查询改角色下是否有用户
		$readmin=Db::name('admin')->find($id);
		if($readmin){
			$this->ajaxShow('201','该角色下有管理员不能删除！');
		}

        $re=Db::name('role')->delete($id);
        if($re){
            $this->ajaxShow('200','添加成功！');
        }else{
            $this->ajaxShow('201','添加失败！');
	    }
	}


	public function lst()
	{
		$role=Db::name('role')->select();
		foreach ($role as $key => $value) {
			# code...
			$role[$key]['role_auth']=$this->strtoarr($value['role_auth']);
			foreach ($role[$key]['role_auth'] as $k => $v) {
				# code...
				$result=Db::name('auth')->field('auth_name')->where('id=:id',['id'=>$v])->find();
				if($result){
					$role[$key]['role_auth'][$k]=$result['auth_name'];
				   //array_push($role[$key]['role_auth'],$result['auth_name']);
				}				
			}
		}

		foreach ($role as $key => $value) {
			# code...
			$role[$key]['role_auth']=$this->arrtostr($value['role_auth']);
		}

		$count=count($role);
		$this->assign('count',$count);	
		$this->assign('role',$role);
		return $this->fetch();
	}

	public function update()
	{
		if($this->request->isAjax()){
       	 	$data=$this->request->post();  

            $data['role_auth']=substr($data['role_auth'],0,strlen($data['role_auth'])-1);

	        $re=Db::name('role')->update($data);
	        if($re){
	            $this->ajaxShow('200','添加成功！');
	        }else{
	            $this->ajaxShow('201','添加失败！');
	        }
       }

        $id=$this->request->param('id');
        //获取全部的权限
        $authcat=Db::name('authcat')->select();
        $auth= Db::name('auth')->select();
        $this->assign([
       	   'authcat'=>$authcat,
       	   'auth'=>$auth
        ]);
        $findOne=Db::name('role')->where('id=:id',['id'=>$id])->find();
        $findOne['role_auth']=$this->strtoarr($findOne['role_auth']);
        $this->assign('findOne',$findOne);
		return $this->fetch();
	}


}