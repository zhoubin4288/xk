<?php

namespace app\admin\model;
use think\Model;

class Company extends Model{
 
  //处理其代码

  public function user(){

     return $this->hasOne('User','id','user_id');
  }


}
