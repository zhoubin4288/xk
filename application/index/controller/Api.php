<?php

namespace app\index\controller;
use think\Controller;
use think\Session;

class Api extends Controller
{
   
    //发送手机短信接口
    public function sendMessage(){
    	/*//配置相关参数
    	//$userport=Confing::get('')
    	$username=confing('username');
    	$password=confing('password');

    	//发送邮件
    	//利用第三方软件而进行处理
    	$this->send($mobile);*/
        
    

    }
   
    //发送电子邮件代码接口
    public function sendEmail(){
        
         
    }
   

 
    //第三方登入
    //QQ登入接口
    public function  loginQQ(){
       //第一步获取配置参数
       //第二步
    }


    //微信登入接口
    public function loginWx(){

    }
  
    //微博登入接口
    

    public function loginWb(){
       /*$config=[
         'WB_AKEY'='',
         'WB_SKEY'='', 
       ];
       $o = new \SaeTOAuthV2(WB_AKEY , WB_SKEY );
       $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
       
       $this->assign('code_url',$code_url);
       $this->fetch();*/
         
    }
    

    //百度地图接口
    public function baiduApi(){

        /* $data=[
           'username'=>'xiaoming',
           'password'=>'1234567',
         ];
    	$this->showM(200,'接口调用成功！',$data);*/

    }

    //echart接口调用

    public function echartApi(){
        //基本上简单的代码而进行处理时。

      /* //调用缓存而进行操作
    	if(cache('user')){
    		$data=cache('user');
    	}else{
    		$data=Db::name('user')->select();
    		cache('user')=$data;
    	}

        //判断数据是否存在
        if($data){
           $this->showM(2001,'获取数据成功！',$data); 	
        }esle{
           $this->showM(200,'获取数据失败！');
        }*/
       
    }


    private function sM($username,$password){
    	
    }
    
     
    //接口返回数据
    private  function showM($code,$msg,$data){
    	$message=[
           'code'=>$code,
           '$msg'=>$msg,
           'data'=>$data
    	];       
        echo json_encode($message,JSON_UNESCAPED_UNICODE);die();
    }
    

    //发送代码封装
    private function send($mobile){
       $mobile=$mobile;	
       $code=$this->getRandNum(4);
       $codeValidate=md5(md5($mobile)."||".md5($code));
       //保存到session数据当中
       //发送代码发送成功则
       Session::set('code',$codeValidate);
    }

    //验证数据代码
    private function check($code,$way){

    	//组装验证代码
    	$codeValidate=md5(md5($code)."||".md5($way));
    	//获取发送的验证代码session
    	if(!$_SESSION('code')){
         echo '没有发送验证码'; exit();
      } 
        if($codeValidate!=$_SESSION['code']){
        	echo '验证码不正确！';exit();
        }

        return true;
    }
        
        //验证码进行对比

    /**
     * 随机生成数字
     * @param  [$num] 生成参数的位数
     * @return [￥code] 返回生成的代码
     */
   /**
    * 随机生成数字
    * @param  [type] $num [description] 生成数字位数
    * @return [type]      [description]
    */
    private function getRandNum($num){
    	
        for($i=0;$i<$num;$i++){
        	$code.=mt_rand(1,9);
        }
        return $code;
    }
    
    public function(){

    }
  




 ?>

