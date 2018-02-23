<?php

namespace app\admin\controller;
use think\Db;
use think\Controller;

class Common extends Controller
{
	public function _initialize()
	{  
        $username='root';
        
        if(!$username){
           $this->success('用户没有登入','User/login');
        }

        if($username != 'root'){
           
          $url=$this->request->module().'/'.$this->request->controller().'/'.$this->request->action();
	        //根据用户名查询出其所对应的所有的权限表中数据
	         echo $url;

	         $re=$this->check($username,$url); 
	         if(!$re){
	         	 $this->error('你的权限不足!');
	         } 
        }       
	}
    

    //权限核对
   private function check($username,$url){
       
       print_r($this->auth($username));

      if(in_array($url, $this->auth($username))){
      	 return true;
      }else{
      	 return false;
      }
   }



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
            $this->error('数据库中没有该用户！');
        }
        foreach ($auth as $key => $value) {
         $auth_url[]=$value['auth_url'];
         }
         return $auth_url;  
   }

   
  //数组转变成为，组合的字符串
	public function arrtostr($data){
		$result=implode(',',$data);
        return $result;   
	}

	//字符串转变成数组
	public function strtoarr($str){
		$result=explode(',', $str);
		return $result;
	}


  public function ajaxShow($code,$msg,$data=""){
       $result['code']=$code;
       $result['msg']=$msg;
       if(!empty($data)){
        $result['data']=$data;
       }        
         echo json_encode($result,true);die();
    }

  //判断是否为手机浏览器
  public function isMobile()
  { 
      // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
      if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
      {
          return true;
      } 
      // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
      if (isset ($_SERVER['HTTP_VIA']))
      { 
          // 找不到为flase,否则为true
          return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
      } 
      // 脑残法，判断手机发送的客户端标志,兼容性有待提高
      if (isset ($_SERVER['HTTP_USER_AGENT']))
      {
          $clientkeywords = array ('nokia',
              'sony',
              'ericsson',
              'mot',
              'samsung',
              'htc',
              'sgh',
              'lg',
              'sharp',
              'sie-',
              'philips',
              'panasonic',
              'alcatel',
              'lenovo',
              'iphone',
              'ipod',
              'blackberry',
              'meizu',
              'android',
              'netfront',
              'symbian',
              'ucweb',
              'windowsce',
              'palm',
              'operamini',
              'operamobi',
              'openwave',
              'nexusone',
              'cldc',
              'midp',
              'wap',
              'mobile'
              ); 
          // 从HTTP_USER_AGENT中查找手机浏览器的关键字
          if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
          {
              return true;
          } 
      } 
      // 协议法，因为有可能不准确，放到最后判断
      if (isset ($_SERVER['HTTP_ACCEPT']))
      { 
          // 如果只支持wml并且不支持html那一定是移动设备
          // 如果支持wml和html但是wml在html之前则是移动设备
          if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
          {
              return true;
          } 
      } 
      return false;
  }

  /**
     * 获取全球唯一标识
     * @return string
     */
    public function uuid()
    {
        return sprintf(
          '%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function wconfig($name,$config){
      $settingstr="<?php \n return [\n";

      foreach($config as $key => $val){
          if($val == 'false') {
            $settingstr.= "\t'".$key."'=>false,\n";
           }elseif ($val == 'true') {
            $settingstr.= "\t'".$key."'=>true,\n";
           }else {
            $settingstr.= "\t'".$key."'=>'".$val."',\n";
           }
      }
        $settingstr.="\n];\n?>";       
        if (!file_put_contents('../application/extra/'.$name.'.php',$settingstr,FILE_USE_INCLUDE_PATH)) {
           $this->error('修改失败，可能是由于没有写入权限导致。');
        }
    }

    //log日志有关调试操作
    public function wlog($string){
      $logstr="日志文件 \n";
      $logstr.=date('Y-m-d H:i:s');
      $logstr.="操作内容".$string;
      if (!file_put_contents('../application/extra/'.date('Y-m-d').'.php',$logstr,FILE_USE_INCLUDE_PATH)) {
           $this->error('修改失败，可能是由于没有写入权限导致。');
        }
    } 

    //追加写入日志相关操作
    public function wzlog($string){
      $logstr="\n\n日志:";
      $logstr.=date('Y-m-d H:i:s');
      $logstr.="\n操作内容:".$string;
      $myfile = fopen('../application/extra/'.date('Y-m-d').'.log', "a") or die("Unable to open file!"); 
      fwrite($myfile, $logstr);
      fclose($myfile);
    } 


   
   //无限极分类相关函数处理
    //递归函数的编写
    protected function _children($data, $parent_id=0, $isClear=TRUE)
    {
        static $ret = array();
        if($isClear)
            $ret = array();
        foreach ($data as $k => $v)
        {
            if($v['parent_id'] == $parent_id)
            {
                $ret[] = $v['id'];
                $this->_children($data, $v['id'], FALSE);
            }
        }
        return $ret;
    }

    //递归所有排序
    protected function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
    {
        static $ret = array();
        if($isClear)
            $ret = array();
        foreach ($data as $k => $v)
        {
            if($v['parent_id'] == $parent_id)
            {
                $v['level'] = $level;
                $ret[] = $v;
                $this->_reSort($data, $v['id'], $level+1, FALSE);
            }
        }
        return $ret;
    }

    protected function deleteImage($path)
    {
        // 先取出图片所在目录
        if(file_exists($path)){
           @unlink($path);
        }
    }

}