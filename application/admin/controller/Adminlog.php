<?php

namespace app\admin\controller;
use think\Db;
use think\Controller;
use app\admin\model\Auth as AuthModel;
use app\admin\controller\Common;

class Adminlog extends Common
{
	public function lst(){
		$admin_log=Db::name('admin_log')->paginate();
		$page=$admin_log->render();

		$count=count($admin_log);
		$this->assign('count',$count);
		$this->assign('admin_log',$admin_log);
		$this->assign('page',$page);
		return $this->fetch();
	}
}