<?php

namespace app\api\controller;
use think\Controller;
use think\Db;
use app\admin\controller\Common;

class Index extends Common{

	public function index(){
         echo '中国人民';

	}

   /**
    * [getImagesCat 获取全部分类]
    * @return [type] [description]
    */
    public function getImagesCat(){
        $data=Db::name('imagescat')->select();
        if(!$data){
        	$this->ajaxShow('400','数据不存在',$data);
        } 
        $this->ajaxShow('200','获取图片分类数据成功！',$data);   
    }
   public function getImagesCat7(){
        $data=Db::name('imagescat')->limit(7)->select();
        if(!$data){
            $this->ajaxShow('400','数据不存在',$data);
        } 
        $this->ajaxShow('200','获取图片分类数据成功！',$data);   
    }
    /**
     * [getImagesByCat 获取分类下的带分页的数据量]
     * @return [type] [description]
     */
    public function getImagesByCat(){
    	if(!$this->request->isGet()){
    		$this->ajaxShow('201','请求方法错误');
    	}
    	$page=$this->request->get('page');
    	if(empty($page)){
    		$this->ajaxShow('201','请求参数page不存在');
    	}
    	$imagescat_id=$this->request->get('imagescat_id');
    	if(empty($imagescat_id)){
    		$this->ajaxShow('201','请求参数imagescat_id不存');
    	}
        //第几页
        $start=($page-1)*2;
    	$data=Db::name('images')->where('imagescat_id=:id',['id'=>$imagescat_id])->limit($start,2)->select();

    	 if(!$data){
        	$this->ajaxShow('400','数据不存在',$data);
        } 
        $this->ajaxShow('200','获取图片分类数据成功！',$data); 
    }
    
    /**
     * [getImagesHot description]
     * @return [type] [description]
     */
    public function getImagesHot(){

    	//默认获取图片热点为10条。
        $data=Db::name('images')->where('hot=:id',['id'=>1])->limit(10)->select();

    	 if(!$data){
        	$this->ajaxShow('400','数据不存在',$data);
         } 
        $this->ajaxShow('200','获取热门图片成功！',$data);	
    }
     
     //获取推荐图片操作
    /*字段recommend是否为1  1推荐 2不推荐    
    */
    public function getImagesRecommend(){

        //默认获取推荐图片为10条。
        $data=Db::name('images')->where('recommend=:id',['id'=>1])->limit(10)->select();

         if(!$data){
            $this->ajaxShow('400','数据不存在',$data);
        } 
        $this->ajaxShow('200','获取推荐图片成功！',$data); 
    }
    /**
    * [getModelCat 获取分类下的数据]
    * @return [type] [description]
    */
    public function getModelCat(){
        
       
    }
       
}