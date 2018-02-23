<?php
namespace app\admin\controller;
use think\Db;;
use think\Controller;
use app\admin\controller\Common;

class Images extends Common{

	public function lst()
	{
		
       //获取图片分类
		 $imagescatdata=Db::name('imagescat')->select();
         $count=Db::name('images')->count();
        //获取图片分类下数据
        //获取imagescat_id
        $imagescat_id=$this->request->get('id')? $this->request->get('id') : "";
        $hot=$this->request->get('hot')? $this->request->get('hot') : 0;
        $recommend=$this->request->get('recommend')? $this->request->get('recommend') : 0;
        
        $where=[];
        if(!empty($imagescat_id)){
          $where['imagescat_id']=$imagescat_id;
        }

        if($hot!=0){
          $where['hot']=$hot;
        }

        if($recommend!=0){
          $where['recommend']=$recommend;
        }

        //分页的内容则相对性较为简单难度并不是很大很容易而进行去分析为啥当时就没有弄清除呢。
        
		    //$images=Db::name('images')->order('id desc')->paginate(10);
        $images=Db::name('images')->where($where)->order('id desc')->paginate(1,false,['query' => request()->param()]); 
        $imagexg=[];
        foreach ($images as $key => $value) {        
            if($value['hot']==1&&$value['recommend']!=1){
               $value['hot']="<span style=\"color: red\">(热点)";
               $value['recommend']="</span>";
            }elseif($value['hot']==1&&$value['recommend']==1){
               $value['hot']="<span style=\"color: red\">(热点,";
               $value['recommend']="推荐)</span>";
            }elseif($value['hot']!=1&&$value['recommend']==1){
               $value['hot']="";
               $value['recommend']="<span style=\"color: red\">(推荐)";
            }elseif ($value['hot']!=1&&$value['recommend']!=1) {
               $value['hot']="";
               $value['recommend']="</span>";
            }
            $imagexg[]=$value;
        }
        $page = $images->render();    
    		$this->assign('count',$count);
    		$this->assign('imagescatdata',$imagescatdata);
    		$this->assign('imagescat_id',$imagescat_id);
        $this->assign('hot',$hot);
        $this->assign('recommend',$recommend);
    		$this->assign('images',$imagexg);
    		$this->assign('page',$page);
		   return $this->fetch();
	}

	public function add(){
		if($this->request->isAjax()){

       	 	$data=$this->request->post();   
       	 	//处理接收的数据
       	 	$data['createtime']=time();
       	 	$data['updatetime']=time();
       	 	

          //验证接收的数据
       	 	// $validate=validate('Images');
          //   if(!$validate->scene('add')->check($data)){
          //       //$this->ajaxShow('201',$validate->getError());
          //       echo $validate->getError(); die();
          //   }
       	 	 
	        $re=Db::name('images')->insert($data,true);

	        if($re){
	           $this->ajaxShow('200','添加成功！');
	        }else{
	            $this->ajaxShow('201','添加失败！');
	            //echo $re->getError();
	        }
       }

       $imagescat=Db::name('imagescat')->select();
       $this->assign('imagescat',$imagescat);
       return $this->fetch();
	}
 
  public function del(){
    	$id=$this->request->param('id');
    	$data=Db::name('images')->where('id=:id',['id'=>$id])->find(); 
        $re=Db::name('images')->delete($id);
        if($re){
        	   
        	   $this->deleteImage('.'.$data['url']);
        	   //unlink('.'.$data['url']);
	           $this->ajaxShow('200','删除成功！');
	        }else{
	            $this->ajaxShow('201','删除失败！');
	            //echo $re->getError();
	    }
  }


    public function update()
	{
		if($this->request->isAjax()){			
       	 	  $data=$this->request->post(); 
            //$this->ajaxShow('200','upload success!', $data);  
            //处理接收的数据     
            $data['updatetime']=time();
            //验证接收的数据
       	    // $validate=Validate('Admin');
            //   if(!$validate->scene('add')->check($data)){
            //       $this->ajaxShow('201',$validate->getError());
            //   } 
           // unlink('.'.$data['imagesorurl']);
          

           if($data['imagesorurl']!=$data['url']){
              $this->deleteImage('.'.$data['imagesorurl']);
            }  

          unset($data['imagesorurl']);	 	 
	        $re=Db::name('images')->update($data);

	        if($re){	          	
	           $this->ajaxShow('200','修改成功');
	          }else{
	            $this->ajaxShow('201','修改失败');
	            //echo $re->getError();
	          }
        }

        $id=$this->request->param('id');
        $imagescat=Db::name('imagescat')->select();       
        $findOne=Db::name('images')->where('id=:id',['id'=>$id])->find();
        $this->assign('imagescat',$imagescat);
        $this->assign('findOne',$findOne);
		    return $this->fetch();
	}


	//图片上传函数处理
    public function uploadImage(){

        $targetFolder = '/uploads'; // Relative to the root

		//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

		if (!empty($_FILES)) {

			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
			
			$timepaht=date('Ymd',time());
			//判断文件是否存在
            $dir = iconv("UTF-8", "GBK", "uploads/".$timepaht);
		    if (!file_exists($dir)){
		            mkdir ($dir,0777,true);
		    }

			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			$targetFile = rtrim($targetPath,'/') . '/'.$timepaht. '/'. md5($_FILES['Filedata']['name']).'.'.$fileParts['extension'];
			$saveName='/uploads/'.$timepaht.'/'.md5($_FILES['Filedata']['name']).'.'.$fileParts['extension'];
            $saveCropName='/uploads/'.$timepaht.'/crop_'.md5($_FILES['Filedata']['name']).'.'.$fileParts['extension'];

  			if (in_array($fileParts['extension'],$fileTypes)) {

  				if(move_uploaded_file($tempFile,$targetFile)){
              //图片上传切为3:4的模式而进行显示                     
              $this->ajaxShow('200','upload success!', $saveName);                   
  				}				
  			} else {
  				  $this->ajaxShow('400','Invalid file type', $targetFile);
  			}
  		}
      
    }

}


?>