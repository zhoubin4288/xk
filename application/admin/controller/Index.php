<?php

namespace app\admin\controller;
use think\Session;
use think\Controller;
use think\Db;

class Index extends Controller
{

    public function index(){
       
        if(!Session::get('username')){
           $this->redirect('admin/admin/login');
        }  
        
        $username=Session::get('username');
        $finusrOne=Db::name('admin')->where('username=:un',['un'=>$username])->find();

        //获取该用户所对应的全部权限
        $module=Db::name('module')->select();
        $authcat=Db::name('authcat')->alias('a')->join('auth b','a.id=b.auth_cat_id')->select();
        foreach ($authcat as $key => $value) {
            # code...
            if(strstr($value['auth_url'],'lst')){
               $authcatfilter[]= $value;
            }
        }
          
        $auth=Db::name('auth')->select();
        $this->assign([
             'finusrOne'=>$finusrOne,
             'authcatfilter'=>$authcatfilter,
             'module'=>$module,
             'auth'=>$auth,
        ]);   
    	return $this->fetch();
    }

    public function  img(){

        if($this->request->isPost()){

            var_dump($this->request->post());
            echo '景德镇';
            die();
        }

        // $targetFolder = '/upload'; // Relative to the root

        // $verifyToken = md5('unique_salt' . $_POST['timestamp']);

        // if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
        //     $tempFile = $_FILES['Filedata']['tmp_name'];
        //     $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
        //     $targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
            
        //     // Validate the file type
        //     $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
        //     $fileParts = pathinfo($_FILES['Filedata']['name']);
            
        //     if (in_array($fileParts['extension'],$fileTypes)) {
        //         move_uploaded_file($tempFile,$targetFile);
        //         echo '1';
        //     } else {
        //         echo 'Invalid file type.';
        //     }
        // }
        return $this->fetch();

    } 
         
}