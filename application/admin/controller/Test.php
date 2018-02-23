<?php

namespace app\admin\controller;
use think\Db;
use think\Controller;

class Test extends Controller
{
	public function index()
	{  
        $username='zhoubin';
        $url=$this->request->module().'/'.$this->request->controller().'/'.$this->request->action();
        //根据用户名查询出其所对应的所有的权限表中数据
         $re=$this->check($username,$url); 
         if($re){
         	echo '权限存在';
         }else{
            echo '你没有权限';
         }    
	}
    

  //权限核对
   private function check($username,$url){
       
      if(in_array($url, $this->auth($username))){
      	 return true;
      }else{
      	 return false;
      }
   }

   //数据库管理系统有关的操作时。
    
   //获取权限所对应url数据
   private function auth($username){
       $result=Db::name('user')->alias('a')->join('role b','b.id=a.role_id')->where('username=:username',['username'=>$username])->find();
        $auth_id=$result['role_auth']; 
       
        if($auth_id) 
        {
        	 $auth_idarr=$this->strtoarr($auth_id);
            if(count($auth_idarr)==1){
	        	 $auth=Db::name('auth')->where('id','eq', $auth_id)->select(); 
	        }else{
	        	 $auth=Db::name('auth')->where('id','in', $auth_id)->select(); 
             }
        }else{
            echo '该用户没有权限！';
        }

        foreach ($auth as $key => $value) {
         	$auth_url[]=$value['auth_url'];
        }
        return $auth_url;  
   }

	//数组转变成为，组合的字符串
	public function arrtostr($data){
		$result=implode($data,',');
        return $result;   
	}

	//字符串转变成数组
	public function strtoarr($str){
		$result=explode(',', $str);
		return $result;
	}

	public function f1(){

		$se=$this->request->post();
		$re=json_encode($se);
		file_put_contents('./1.txt', $re);
		echo json_encode(['a1'=>"dfdfd","a2"=>"djfd"]);
	}
  
  public function  img(){


     return $this->fetch();
  }

}
