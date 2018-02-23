<?php

namespace app\admin\controller;
use app\admin\model\Brand as BrandModel;
use think\Image;
use think\Controller;

class Brand extends Controller
{
	public function add(){
		if($this->request->post('dosubmit')){
			$data=$this->request->post();
	        $imageurl=$this->getImageThumbUrl(80,80);
	        //助手函数
	        $data['image']=$imageurl['savename'];
	        $data['thumb']=$imageurl['savethumbname'];
	        
	        $validate=validate('Brand');
	        if(!$validate->scene('add')->check($data)){
	        	$this->error($validate->getError());
	        }

	       $re= BrandModel::create($data,true);
 
           var_dump($re);
           die();
	       if($re){
	       	  $this->success('添加成功！','Brand/lst');
	       }else{
	       	  $this->error('添加失败！');
	       }
		}
		return $this->fetch();
        
	}

	public function update(){
        $id=$this->request->param('id');
        $findOne=BrandModel::find($id);
	}

	public function lst(){
     $brand=BrandModel::all();
     foreach($brand as $v){
     	$v['image']=$this->request->domain().'/uploads/'.$v['image'];
        $v['thumb']=$this->request->domain().'/uploads/'.$v['thumb'];
     }         
     $this->assign('brand',$brand);
     return $this->fetch();      
	}

	public function del(){
        $id=$this->request->param('id');
        //获取数据
        $findOne=BrandModel::find($id);
        if(BrandModel::destroy($id)){
        	unlink('uploads/'.$findOne['image']);
        	unlink('uploads/'.$findOne['thumb']);
        	$this->success('删除成功！','Brand/lst');
        }

	}

    /**
    * 上传图片并压缩成缩列图返回图片地址
    */
	private function getImageThumbUrl($widht,$height){
		$file=$this->request->file('image');
		$data=array();
		if($file){
			$imagePath=ROOT_PATH.'public'.DS.'uploads';
			$info=$file->move($imagePath);
			if($info){
				$savename=$info->getSaveName();
				$filename=$info->getFileName();
				$datefile=str_replace($filename, "", $savename);				
				$savethumbname=$datefile.'thumb_'.$filename;
                $image=Image::open('uploads/'.$savename);
                $re=$image->thumb($widht,$height)->save('uploads/'.$savethumbname);
                if(!$re){
                   $this->error('生成缩列图出现问题！');
                }
                $data['savename']=$savename;
                $data['savethumbname']=$savethumbname;
			}
			return $data;
		}
	}
    
    /**
    * 上传图片并压缩成缩列图返回图片地址
    */
    private function getImageUrl(){
        $file=$this->request->file('image');
		if($file){
			$imagePath=ROOT_PATH.'public'.DS.'uploads';
			$info=$file->move($imagePath);
			if($info){
				$savename=$info->getSaveName();               
			}
			return $savename;
		}

    }
	/*private function delImage($filepath){
		unlink($filepath)
	}*/
    private function showImage($imageUrl){   

        return 'public/'.$imageUrl;
    }  

    public function orderBrand($data){
         foreach($data as $key=>$v){
             BrandModel::update($v);
         }
    }
}

