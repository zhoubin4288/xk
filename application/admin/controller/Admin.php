<?php

namespace app\admin\controller;

use think\Db;
use app\admin\model\Admin as AdminModel;
use app\admin\controller\Common;
use think\Config;
use think\Session;


class Admin extends Common
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
       	 	$validate=validate('Admin');
            if(!$validate->scene('add')->check($data)){
                //$this->ajaxShow('201',$validate->getError());
                echo $validate->getError(); die();
            }
       	 	 
            unset($data['repassword']);

	        $re=Db::name('admin')->insert($data,true);

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
        $re=Db::name('admin')->delete($id);
        if($re){
	           $this->ajaxShow('200','添加成功！');
	        }else{
	            $this->ajaxShow('201','添加失败！');
	            //echo $re->getError();
	    }
    }

    public function duodel(){
    	$data=$this->strtoarr($this->request->post('delid'));
    	foreach ($data as $key => $value) {
    		# code...
    		$re=Db::name('admin')->delete($id);
	        if(!$re){
		        $this->ajaxShow('201','删除不成功！');
		    }
		    $this->ajaxShow('200','删除成功！');
		  }	    	
    }

    public function lst()
	{
  		$admin=Db::name('admin')->select();
  		$count=count($admin);
  		$this->assign('count',$count);
  		$this->assign('admin',$admin);
        
        //保存文件配置参数
        //var_dump(Config::get('setting'));
        //die();
        $this->wzlog('4556565656zhoubinxiaopengyouerjinxnyouguanwentichulishi');
        // $this->wconfig('setting',$admin[0]);

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
       	 	$validate=Validate('Admin');
            if(!$validate->scene('add')->check($data)){
                $this->ajaxShow('201',$validate->getError());
            }
       	 	 

	        $re=Db::name('admin')->update($data);
	        if($re){
	        	$this->success('修改成功！','admin/lst');
	        }else{
	        	$this->error('修改失败!');
	        }

       }
        $id=$this->request->param('id');
        $role=Db::name('role')->select();       
        $findOne=Db::name('admin')->where('id=:id',['id'=>$id])->find();
        $this->assign('role',$role);
        $this->assign('findOne',$findOne);
		return $this->fetch();
	}


	private function generate_key( $length = 12 ) { 
		// 密码字符集，可任意添加你需要的字符 
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|'; 
		$key = ''; 
		for ( $i = 0; $i < $length; $i++ ) 
		{ 
		// 这里提供两种字符获取方式 
		// 第一种是使用 substr 截取$chars中的任意一位字符； 
		// 第二种是取字符数组 $chars 的任意元素 
		// $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1); 
		$key .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
		} 
		return $key; 
	} 
  
  

  public function login(){
  
      //获取登入表单数据
      if($this->request->isAjax()){

         $data=$this->request->post();
          
         $username=$data['username'];
         $password=$data['password'];
         $captcha=$data['captcha'];
         $isopencaptcha=1;
         //对数据内容而进行验证相关的操作


         //判断验证码
         if($isopencaptcha){
            if(!captcha_check($captcha)){
             $this->ajaxShow('201','验证码错误');
            }
         }
          
          
         //判断用户名
         $result=Db::name('admin')->field('id,logins,status,password,key')->where('username=:un',['un'=>$username])->find();
        // $result=Db::name('auth')->field('auth_name',)->where('id=:id',['id'=>$v])->find();
         if(!$result){
            $this->ajaxShow('201','用户名不存在');
         }
         $password=md5(md5($password).$result['key']);
         //echo $password;die();
         //判断用户密码
         if($password!=$result['password']){
            $this->ajaxShow('201','用户密码错误！');  
         }

         if($result['status']==1){
            $this->ajaxShow('201','该用户被禁用！');
         }


       
         //修改admin当中数据
         $updatedata['id']=$result['id'];
         $updatedata['last_login_ip']=$this->request->ip();
         $updatedata['login_time']=time();
         $updatedata['logins']=$result['logins']+1;

         $re=Db::name('admin')->update($updatedata);        
         if(!$re){
            $this->ajaxShow('201','登入修改数据错误'); 
          }

          Session::set('username',$username);  
          //插入日志数据
          $insertdata['admin']=$username;
          $insertdata['ip']=$this->request->ip();
          $insertdata['time']=time();
          Db::name('admin_log')->insert($insertdata);

          $this->ajaxShow('200','登入成功！');

      }

      if(Session::get('username')){        
         $this->redirect('admin/index/index');
      }
      return $this->fetch();
      
  }


  public function loginout(){
      Session::delete('username');
  }

  public function find(){


        // $data=AdminModel::find();
        // //data数据是会注入到model变量当中。
        // //model型中新增函数需要包括多种
        // $admin=new AdminModel();
        // $damin->name="dfdsf";
        // $admin->save();
        // //
        // $admin=new AdminModel();
        // $admin->data(['dfdf'=>'dfs','df'=>'dfd']);
        // $admin->allowField('d','df','dfsd')->save();
        // //
        // $admin=new AdminModel(); 
        // //
        // $admin=new AdminModel(["dfd"=>'dfhf','dfd'=>"dfds"]);
        // $admin->allowField('dfd','dff','duh')->save();
        // //
        // //如何进行有效的进行处理分析时。
        // $admin=new AdminModel();
        // $list=[
        //   ['dffd'=>'dfdsf','dfe'=>'hduhf'],
        //   ['dffd'=>'dfdsf','dfe'=>'hduhf'],
        //   ['dffd'=>'dfdsf','dfe'=>'hduhf']          
        // ];
        // $admin->sveAll($list);
        
        // $admin=AdminModel::create(['dfdf'=>'dfdf','dfd'=>'dsjfh']);
        // echo $admin->dfdf;
        // echo $admin->dfd;

        // $admin=model('admin');
        // $list=[
        //   ['dffd'=>'dfdsf','dfe'=>'hduhf'],
        //   ['dffd'=>'dfdsf','dfe'=>'hduhf'],
        //   ['dffd'=>'dfdsf','dfe'=>'hduhf']          
        // ];
        // $admin->sveAll($list);
       //  // //总结：有三种方法创建model。一、new adminModel() 二、adminmodel::create() 三、

       //  //更新相关的操作处理
       //  $admin=AdminModel::get(1);
       //  $admin->xiao='dfdf';
       //  $admin->xiao='dfdf';
       //  $admin->djkfj='dfddfdf';
       //  $admin->save();
       //  //
       //  $admin=new AdminModel();
       //  $admin->allowFile(['dfd','fd'])->save(['id'=>'dfdsf'],['id'=>1]);
       //  //弄了这么多的方法不知道其效果会有怎样的应用时。

       //  //批量更新操作这应用更新是则这种相关的操作数据。
       //  $list=[
       //    ['id'=>1,'dfd'=>'dfdf','dfdf'=>'zhongguo'],
       //    ['id'=>1,'dfd'=>'dfdf','dfdf'=>'zhongguo'],
       //    ['id'=>1,'dfd'=>'dfdf','dfdf'=>'zhongguo'],   
       //  ]
       // $admin->save($list); 
       //也就是在批量操作则有用。查询数据则问题对应的比较多时并不是很好处理
        //其用到的东西比较少。
        $admin=AdminModel::find(8);
        
        //$admin->username='zhoubidfdfn';

        $admin->save();

        echo $admin->last_login_ip;
        echo $admin->username;
        echo $admin->status;

  }  

}