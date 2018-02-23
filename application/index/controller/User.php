<?php

namespace app\index\controller;
use app\index\controller\User;
use think\Request;

class User extends Common
{
   public function add(){
   	 $this->fetch();
   }
}