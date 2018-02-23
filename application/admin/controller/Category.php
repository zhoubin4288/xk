<?php
 
 namespace app\admin\controller;
 use think\Db;
 use think\Validate;
 use think\Controller;
 use app\admin\controller\Common;

 class Category extends Controller
 {
     public function lst ()
     {
        
         $category=Db::name('Category')->select();
         //对category数据而进行递归整合
         $category=$this->_reSort($category);         
         echo $this->_childstring($category,1);

         die();



         foreach ($category as $key => $value) {
             $value['level']=str_repeat('--',$value['level']);
             $data[]=$value;
         }
         //分页类的使用
         //$page=new Page($data,2);     
         //$this->assign('page', $page->pageHtml());
         //$this->showAjax('200','Category/lst',$data);
        //$page=$category->render();
        $count=count($data);
        $this->assign('count',$count);
        /* $this->assign('authcat',$authcat);
        $this->assign('page',$page);*/
         $this->assign('category',$data);
         return $this->fetch();
     }

     public function add()
     {        
         if($this->request->isAjax()){
             $data=$this->request->post();
             //数据过滤
             $validatedata=[
                'name'=>'require|max:50',
                'introduce'=>'require|max:150'
             ];
             $Validate=new Validate($validatedata);
             if(!$Validate->check($data)){
                 return $Validate->getError();
             }
             $re=Db::name('category')->insert($data);  
             if($re){
                $this->ajaxShow('200','添加成功');
             }else{
                $this->ajaxShow('201','添加失败！');
             } 
         }
         //获取分类列表
         $category=Db::name('category')->select();
         $category=$this->_reSort($category);
         foreach ($category as $key => $value) {
             $value['level']=str_repeat('--',$value['level']);
             $data[]=$value;
         }
         $this->assign('data',$data);
         return $this->fetch();
     }

     public function update()
     {
         if($this->request->isAjax()){
             $data=$this->request->post();
             //数据过滤
             $validatedata=[
                'name'=>'require|max:50',
                'introduce'=>'require|max:150'
             ];
             $Validate=new Validate($validatedata);
             if(!$Validate->check($data)){
                 return $Validate->getError();
             }
             $re=Db::name('category')->update($data);
              if($re){
                $this->ajaxShow('200','修改成功');
             }else{
                $this->ajaxShow('201','修改失败！');
             }  
         }

         //获取分类数据和当前数据
         $category=Db::name('category')->select();
         $category=$this->_reSort($category);
         foreach ($category as $key => $value) {
             $value['level']=str_repeat('--',$value['level']);
             $data[]=$value;
         }
         $id=$this->request->get('id');
         if($id){
             $findOne=Db::name('category')->find($id);
         }
         $this->assign('data',$data);
         $this->assign('findOne',$findOne);
         return $this->fetch();
     }

     public function del()
     {
         $id=$this->request->param('id');        
         if($id){
             //发现id中有子类则不能删除或者全部的删除子类
             $category=Db::name('category')->select();
             if(!empty($this->_children($category,$id))){
                 $this->ajaxShow('201','该数据下有子类不能删除');
             }else{
                 $findOne=Db::name('category')->delete($id);
                 if($findOne){
                        $this->ajaxShow('200','修改成功！');
                 }else{
                        $this->ajaxShow('201','修改失败！');
                 }
             }
             
         }

     }

   //获取子类下全部的字段id，如果有则全部显示包括自己本身的id，如果没有则显示自己的id值。  
   protected function _childstring($category,$id){
        
        $category=$this->_reSort($category,$id);
        foreach ($category as $key => $value) {
            $data[]=$value['id'];
        }
        
        if(empty($data)){
             return $id;
        }else{
            return  $id.','.implode(',', $data);
        }
       
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



 }