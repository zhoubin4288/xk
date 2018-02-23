<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
	
    //protected $autoWriteTimestamp = 'datetime';
   
    //这些数据
	public function getStatusAttr($value){
		$status=[-1=>'删除',0=>'禁止',2=>"待审核"];
		return $status[$value];
	}

	
    protected function getUsernameAttr($value){
		return  strtoupper($value);
	}


	protected function setUsernameAttr($value){
		return  strtoupper($value);            
	}

	//自动完成操作
	protected $auto=[];
	protected $insert=['last_login_ip','status'=>1];
	protected $update=['last_login_ip'];

	protected function setLastLoginIpAttr()
	{
		return '127.0.7.1';
	}

    public function comments(){
   	  return $this->hasMany('Comment','art_id','id',['a','b']);
    }



}