<?php

namespace app\admin\model;
use think\Model;

class User extends Model{
 
  //处理其代码
  protected function setNameAttr($value){
      return strchr($value); 
  }

  protected function getNameAttr($value){
  	return $this->change($value);
  }
  public function company(){
     return $this->hasMany('Company','user_id','id');
  }


  public function change($value){

    //处理有关相关的函数对应的操作分析时。
    //其各种代码关系量的祝贺。  

  }

}
