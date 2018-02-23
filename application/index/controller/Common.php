<?php

namespace app\index\controller;
use think\Controller;
use think\Session;
use think\Request;
use think\Db;

class Common extends Controller
{
    public function _initialize(){

    	//判断用户是否登入
    	$this->islogin();
    	//判断权限是否存在
    }

   /**
   **判断用户是否登入函数
   */
    public function islogin(){

    	//if(isset(Session::get('username'))){  		
       //     $this->error('用户没有登入操作！')
    	//}
    }
    /**
     * [order jfid]
     * @return [type] [dfjijiaf辅导费]
     */
    public function order(){

      $username='zhoubin';
      $id=1;
      $this->killjifen($username,$id);
    }  
    
    //扣除需浏览该文章所需要的积分
    public function killjifen($username,$id){
        //  
        //查询商品或文章所需要对应的积分
        $result=Db::name('user')->where('username=:username',['username'=>$username])->find();
        $user_id=$result['id'];
        $jifen=$this->getjf($id);
        //扣除用户所需的积分，并插入购买关联表中
        Db::startTrans();
        try{
          Db::execute("update xk_user set jifen=jifen-$jifen where username='$username'");
          $data=[
           'crush_id'=>$id,
           'user_id'=>$user_id,
           'time'=>time(),
           'jifen'=>$jifen;
          ]; 
          //rizhi
          Db::name('trans')->insert($data);         
          Db::commit();
        }catch(\Exception $e){
          Db::rollback();
          echo 'shibailejiejie';
        }
       
    }

    public function getjf($id){
      $result=Db::name('crush')->find($id);
      return $result['crush_jifen'];
    }
   
  
  

}