<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Validate;
use app\admin\controller\Common;

class Grind extends Common
{
    public function add()
    {
       if($this->request->isAjax()){
          $data=$this->request->post();
          //$string=json_encode($data);
          //$this->wzlog($string);

          $validate=new Validate();
          if(!$validate->scene('add')->check($data)){
             $this->ajaxShow('201',$validate->getError());
          }

          
          $re=Db::name('grind')->add($data);
          if($re){
            $this->ajaxShow('200','添加磨机数据成功！');
          }else{
            $this->ajaxShow('2001','添加数据失败！');
          }

       }
       

        //获取分类数据
        $category=Db::name('category')->select();
        if(!$category){
            $this->ajaxShow('201','获取分类失败！');
        }

        $category=$this->_reSort($category);
        foreach ($category as $key => $value) {
             $value['level']=str_repeat('--',$value['level']);
             $cat[]=$value;
        }
       $this->assign('cat',$cat);      
       return $this->fetch();
    }
  
    public function update()
    {
       if($this->request->isAjax()){
          $data=$this->request->post();
          $validate=new Validate();
          if(!$validate->scene('add')->check($data)){
             $this->ajaxShow('201',$validate->getError());
          }
          $re=Db::name('grind')->add($data);
          if($re){
            $this->ajaxShow('200','添加磨机数据成功！');
          }else{
            $this->ajaxShow('2001','添加数据失败！');
          }

       }
       //获取单条数据
       $id=$this->request->param('id');
       if(empty($id)){
          $this->ajaxShow('201','参数id值错误！');
       }
       $findOne=Db::name('grind')->where('id=:id',[':id'=>$id]);
       if(!$findOne){
          $this->ajaxShow('201','磨机单条数据获取失败！');
       }

       //获取分类数据
        $category=Db::name('category')->select();
        if(!$category){
            $this->ajaxShow('201','获取分类失败！');
        }

        $category=$this->_reSort($category);
         foreach ($category as $key => $value) {
             $value['level']=str_repeat('--',$value['level']);
             $cat[]=$value;
        }

       $this->display('cat',$cat);
       $this->display('findOne',$findOne);
       return $this->fetch();

    }

    public function del()
    {
      
    }


    public function lst()
    {
        $data=Db::name('grind')->select();
        $count=count($data);
        $this->assign('count',$count);  
        $this->assign('data',$data);
        return $this->fetch();
    }

    


}