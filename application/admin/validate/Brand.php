<?php

namespace app\admin\validate;

use think\Validate;

class Brand extends Validate
{
	protected $rule=[
       'name'=>'require|max:30',
       'name'=>'require|min:6',
       'info'=>'require|min:10',
       'info'=>'require|max:255',
	];


	protected $message=[

       'name.require'=>'品牌名必须存在',
       'name.min'=>'品牌名最大长度至少6',
       'name.max'=>'品牌名最大长度不超出30',
       'info.between'=>'说明长度必须在10到255之间',

	];


	protected $scene=[
       'add'=>['name','info','id'],
       'update'=>['name','info'],
	];
}