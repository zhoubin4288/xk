<?php

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
	protected $rule=[
        'catid'=>'require',
        'name'=>'require|length:32',
        'length'=>'require|float',
        'width'=>'require|float',,
        'efflength'=>'require|float',,
        'effwidth'=>'require|float',,
        'product'=>'require|length:32', 
        'country'=>'require|length:32',
        'inpower'=>'require|number',
        'acpower'=>'require|number',
       
	];

	protected $message=[
        'catid.require'=>'磨机分类不得为空',
        'name.require'=>'磨型号不的为空',
        'name.length'=>'类型名称不得超过32位',
        'length.require'=>'长度必须填写',
        'repassword.require'=>'再一次输入密码不得为空',
        'repassword.confirm'=>'两次输入密码不一样',
        'email'=>'邮箱格式不正确',
        'phone'=>'手机格式不正确',
        'status.require'=>'状态必须勾选',
	];

	protected $scene=[
        
        'add'=>['username','password','repassword','email','phone','status'],
        'update'=>['password','repassword','email','phone','status'],

	];


}