<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Db;
use think\Validate;
use think\Session;

class Article extends Controller
{
    public function index()
    {
        return "成功跳转到该页面中";    
		
    }

    //修改自己所做的代码进行优化操作时。
    
	public function lst()
	{
		 //$db= DB::table('xk_article')->select();	
		 //查询条件有关相关的处理
		$where="";
		if($this->request->get('dosubmit')){
			
            if($this->request->get('title')){
            	$where.='a.title like "%'.$this->request->get('title').'%"';
            }elseif($this->request->get('tag')){
                $where.='and a.tag  like "%'.$this->request->get('tag').'%"';
            }
           //Session::set('article_where',$where);           			
		}
		 //$where=Session::get('article_where');
		 //可以利用缓存技术查询时候可以利用缓存技术处理
		 $db= DB::name('article')->alias('a')->join('category c','a.catid=c.id')->join('content b','a.id=b.id')->where($where)->paginate(1);
		// return view();
         $page=$db->render();
		//$db= db('article')->select();
		// $view=new View();
		 //$view->fetch('show');
		 //注册变量数据进入到其系统中而进行分析对应相关的问题提示
		 // var_dump($db);
		 // die();
		 //显示图片的路径调整
		 foreach($db as $key=>$val){
		 	//显示图片则是硬路径而进行有关的操作时
		 	$val['image']='/xk/public/upload/'.$val['image'];
		 	$val['thumb']='/xk/public/upload/'.$val['thumb'];
            $data[]=$val;
		 }
         //分页类的使用操作
          if(empty($data)){
          }else{
          	$this->assign('article', $data); 
          }		  
		  $this->assign('page',$page);
          return $this->fetch();
	}

	public function add()
	{
		if($this->request->post('dosubmit')){

			$data=$this->request->post();           
            
			$validatedata=[
                'title'=>'require|max:25',
                'introduce'=>'require|max:225',
                'source'=>'require|max:50',             
			];

            $validate=new Validate($validatedata);
            if(!$validate->check($data)){
            	return $validate->getError();
            }            
            //data数据进行补充添加数据
            $data['addtime']=time();
            $data['hits']=0;
            $articledata=$data;
            //对data中content数据进行移除
            //图片上传和图片压缩处理所对应的相关操作则操作比以前相对来说简单点
            $file= $this->request->file('image');

            if($file){

            	$info=$file->move(ROOT_PATH.'public'.DS.'upload');

            	if($info){
            		$imageName= $info->getSaveName();
            		$imageTime=str_replace($info->getFileName(), "", $imageName);
            		$imageThumbName=$imageTime.'thumb_'.$info->getFileName();
            		//上传成功然后处理图片
            	    //打开图片
            		$image= \think\Image::open($file);
                    $image->thumb(150,150)->save(ROOT_PATH.'public'.DS.'upload/'.$imageThumbName);
                    $articledata['image']=$imageName;
                    $articledata['thumb']=$imageThumbName;
            	}
                
            }

            //处理图片压缩功能相关的数据操作时。
            //$image= \think\Image::open('./public/upload/20171002/206b296214b8ee8d7b93782ef44495b6.jpg');           
            unset($articledata['content']);   
            unset($articledata['dosubmit']);   

            $re= Db::name('article')->insert($articledata);
            if($re){
            	//对content表进行添加操作
            	$content=[
		            'id'=>Db::name('article')->getLastInsID(),
		            'content'=>$data['content']
		        ]; 
            	if(Db::name('content')->insert($content)){
            		$this->success('添加成功','Article/show');
            	}else{
            	    $this->error('添加内容数据失败！');
            	}
            
            }else{
            	$this->error('添加失败！');
            }
		}
		//获取分类列表
         $category=Db::name('category')->select();
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
		//先获取有关全部的数据
		if($this->request->post('dosubmit')){

			$data=$this->request->post();
			$validatedata=[
                'title'=>'require|max:25',
                'introduce'=>'require|max:225',
                'source'=>'require|max:50',             
			];

            $validate=new Validate($validatedata);
            if(!$validate->check($data)){
            	return $validate->getError();
            }            
            //data数据进行补充添加数据
            $data['updatetime']=time();
            $articledata=$data;
            //对data中content数据进行移除
             //图片上传和图片压缩处理所对应的相关操作则操作比以前相对来说简单点
            $file= $this->request->file('image');
            if($file){
            	$info=$file->move(ROOT_PATH.'public'.DS.'upload');
            	if($info){
            		$imageName= $info->getSaveName();
            		$imageTime=str_replace($info->getFileName(), "", $imageName);
            		$imageThumbName=$imageTime.'thumb_'.$info->getFileName();
            		//上传成功然后处理图片
            	    //打开图片
            		$image= \think\Image::open($file);
                    $image->thumb(150,150)->save(ROOT_PATH.'public'.DS.'upload/'.$imageThumbName);
                    $articledata['image']=$imageName;
                    $articledata['thumb']=$imageThumbName;
                    //删除原先的图片
            	    //unlink('./upload/'.$data['thumb']);
                    unlink('./upload/'.Session::get('article_image'));
                    unlink('./upload/'.Session::get('article_thumb'));
            	}
            }
            //处理图片压缩功能相关的数据操作时。
            //$image= \think\Image::open('./public/upload/20171002/206b296214b8ee8d7b93782ef44495b6.jpg');                   
            unset($articledata['content']);   
            unset($articledata['dosubmit']);         
            $re= Db::name('article')->update($articledata);
            if($re){            	
            	//对content表进行添加操作
            	$content=[
		            'id'=>$this->request->post('id'),
		            'content'=>$data['content']
		        ];

		        print_r($content); 
            	if(Db::name('content')->update($content)){
            		$this->success('修改成功','Article/show');
            	}else{
            			$this->success('你的内容没有修改','Article/show');
            	}
            
            }else{
            	$this->success('你的文章没用修改','Article/show');
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
		//获取有关对应的值数据
		$id=$this->request->get('id');
		if($id){
		  $findOneData=Db::name('article')->alias('a')->join('content b','b.id=a.id')->where('a.id=:id',['id'=>$id])->find();
          if($findOneData){
          	 //保存已经获取到的图片和地址
          	 Session::set('article_image',$findOneData['image']);
          	 Session::set('article_thumb',$findOneData['thumb']);
           	 $findOneData['thumb']='/xk/public/upload/'.$findOneData['thumb'];
          	 $this->assign('findOneData',$findOneData);
          	 return $this->fetch();
           }

	    }
    }
 
    public function delete(){
    	$id=$this->request->get('id');
    	if($id){
    		//获取图片值
    		$data=Db::name('article')->where('id=:id',['id'=>$id])->find();   		
    		if(Db::name('article')->delete($id)){
    			//对图片和缩列图而进行删除  
                unlink('./upload/'.$data['image']); 			
    			unlink('./upload/'.$data['thumb']);
    			//对内容content进行删除
    			if(Db::name('content')->delete($id)){
    				$this->success('删除成功！','Article/show');
    			}else{
    				$this->error('删除内容失败！');
    			}
    			
    		}else{
                $this->error('删除失败！');
    		}
    	}
    }

	public function test(){
	    $image= \think\Image::open('./upload/20171002/206b296214b8ee8d7b93782ef44495b6.jpg');
        echo $image->width();
        echo $image->height();
        print_r($image->size()) ;
        echo $image->mime();
        ECHO  dirname(__FILE__);
        die();
	}

    /* //递归所有排序
    private function _reSort($data, $parentid=0, $level=0, $isClear=TRUE)
    {
        static $ret = array();
        if($isClear)
            $ret = array();
        foreach ($data as $k => $v)
        {
            if($v['parentid'] == $parentid)
            {
                $v['level'] = $level;
                $ret[] = $v;
                $this->_reSort($data, $v['id'], $level+1, FALSE);
            }
        }
        return $ret;
    }*/

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

