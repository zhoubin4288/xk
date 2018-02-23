<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Company as CompanyModel;
class Company extends Controller{
	public function add(){


		return $this->fetch();
	}


	public function update(){

	}


	public function lst(){
       
        $company=CompanyModel::find(1);
        var_dump($company->user->password);
       // print_r($company);
       
        die();

		return $this->fetch();
	}

	public function delete(){


	}


}

?>