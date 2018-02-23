<?php

namespace app\index\validate;

use think\Validate;

class Order extends Validate
{
	protected $rule=[
        'goodname'=>'require|min:4',
	];


	protected $msg=[
       'goodname'=>'产品名称不能为空！',
	];


	protected $scene=[
      'add'=>['id','jinengjiqiao','jianxi'],

	]

	
}