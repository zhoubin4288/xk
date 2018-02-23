<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\User as userModel;
use think\Db;
use app\admin\controller\Common;
//extends Common
class User extends Controller
{
	public function add()
	{
       
       if($this->request->post('dosubmit')){
       	
       	 	$data=$this->request->post();  
       	 	unset($data['dosubmit']);      
	        $re=Db::name('user')->insert($data,true);
	        if($re){
	        	$this->success('添加成功','User/lst');
	        }else{
	        	$this->error('添加失败!');
	        }
       }
       $role=Db::name('role')->select();
       $this->assign('role',$role);
       return $this->fetch();
	}


	public function del()
	{
		#dj
		$id=$this->request->param('id');
        $re=Db::name('user')->delete($id);
        if($re){
        	$this->success('删除成功！','User/lst');
        }else{
        	$this->error('删除失败！');
        }
        
		return $this->fetch();
	}


	public function lst()
	{
		$user=Db::name('user')->select();
		if(!$user){
			return '取出的数据为空值';
		}
		$this->assign('user',$user);
		return $this->fetch();
	}

	public function update()
	{
		if($this->request->post('dosubmit')){
       	 	$data=$this->request->post(); 
       	 	unset($data['dosubmit']);       
	        $re=Db::name('user')->update($data);
	        if($re){
	        	$this->success('修改成功！','User/lst');
	        }else{
	        	$this->error('修改失败!');
	        }
       }
        $id=$this->request->param('id');
        $role=Db::name('role')->select();       
        $findOne=Db::name('user')->where('id=:id',['id'=>$id])->find();
        $this->assign('role',$role);
        $this->assign('findOne',$findOne);
		return $this->fetch();
	}


	public function ajaxfind($username){
		$re=Db::name('user')->where('username=:username',['username'=>$username])->find();
		if($re){
			return 1;
		}else{
			return 0;
		}
	}



	public function center(){
		$data=[
           ['id'=>1,'name'=>'zhoubin1','parent_id'=>0],
           ['id'=>2,'name'=>'zhoubin2','parent_id'=>0],
           ['id'=>3,'name'=>'zhoubin3','parent_id'=>1],
           ['id'=>4,'name'=>'zhoubin4','parent_id'=>1],
           ['id'=>5,'name'=>'zhoubin5','parent_id'=>3],
           ['id'=>6,'name'=>'zhoubin6','parent_id'=>5],  
		];
         
        //获取分类数据  
        //$data=Db::name('category')->cache()->select();
        $result= $this->showCategory($data,0);
        print_r($result);

	}
    
    

    //无限分级代码而继续处理
	private function getChild($data,$id,$level=0){
		static $result=array();
        foreach ($data as $key => $value) {
        	# code...
        	if($value['parent_id']==$id){
        		$value['level']=$level;
        		$result[]=$value;
        		$level_d=$level+1;
        		$this->getChild($data,$value['id'],$level_d);
        	}
        }
        $level=$level;
        return $result;
	}
    //显示无限分级类名称
	private function showCategory($data,$id){
		$data=$this->getChild($data,$id);
		foreach ($data as $key => $value) {
			# code...
			$value['name']=str_repeat('|--', $value['level']).$value['name'];
			$result[]=$value;
		}
		return $result;
    }



    public function find(){

      echo request()->controller();
      print_r(request());
      die();

       $user=userModel::get(1);
       foreach($user->company as $key=> $value){
       	  $data[$key]['name']=$value['name'];
       	  $data[$key]['telphone']=$value['telphone'];
       }
       
       print_r($data);

    }

    /**
     * 发送邮箱
     */

    public function sends() {  
         
            $tags ='d1';  
            $content = "<p style='text-align:center;'><a href='www.mining120.com'>点击</a></p>";  
            $title = "中国选矿技术网";  
            //$subscribe = "";  
            // $list = $subscribe->where('type_id=%d',$tags)->field('email')->select();              
            // $lists = array_column($list,'email');  
            // $address = array_flip(array_flip($lists)); //获取订阅该类会议的邮箱,重复的邮箱不再重复取  
            $address=array('email'=>"1196684330@qq.com");  
            $this->sendMail($address, $title, $content);  
         
    }  
      
    /* 
     * 邮件发送功能 
     */  
    public function sendMail($address, $title, $content) {  
        $mail=new \PHPMailer();  
        $mail->CharSet = "utf-8";  //设置采用utf8中文编码  
        $mail->IsSMTP();    //设置采用SMTP方式发送邮件
        $mail->IsHTML(true);   //设置内容是否为html类型   
        //$mail->SMTPSecure = 'tls';
        $mail->SMTPSecure = 'ssl';
        $mail->setLanguage('zh_cn');                   
        $mail->Host = "smtp.163.com";    //设置邮件服务器的地址  smtp.qq.com  
        $mail->Port = 25;     //设置邮件服务器的端口，默认为25  gmail  443  
        $mail->From = "15829011932@163.com";  //设置发件人的邮箱地址  
        $mail->FromName = "小码矿-中国选矿技术网站";                       //设置发件人的姓名  
        $mail->SMTPAuth = true; // 设置SMTP是否需要密码验证，true表示需要 
        //$mail->SMTPSecure = 'ssl'; // 使用安全协议  
        $mail->Username = "15829011932@163.com";  
        $mail->Password = "453678453678bin";  
        $mail->Subject = $title;   //设置邮件的标题  
        $mail->AltBody = "text/html";    // optional, comment out and  test  <a href="">abc</a>  
        $mail->Body = $content;//发送的内容  
                                           
        //$mail ->WordWrap = 50;                                 //设置每行的字符数  
        //$mail->AddReplyTo("1196684330@qq.com", "小码矿-中国选矿技术网站");     //设置回复的收件人的地址  
        foreach ($address as $k => $v) {  
            $mail->AddAddress($v);     //设置收件的地址  
        }  
        if (!$mail->Send()) {          //发送邮件  
            $this->error('fail:'.$mail->ErrorInfo);  
        } else {  
            $this->success('发送成功!');  
        }  
    }
    
}