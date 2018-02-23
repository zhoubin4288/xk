<?php

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
	protected $rule=[
        'username'=>'require|alphaDash|length:6,15',
        'password'=>'require|length:32',
        'repassword'=>'require|confirm:password',
        'last_login_ip'=>'ip',
        'status'=>'require', 
        'email'=>'email',
        'phone'=>['regex'=>'^1(3|4|5|7|8)\d{9}$'],
	];

	protected $message=[
        'username.require'=>'用户名不得为空',
        'username.alphaDash'=>'用户名必须字母数字下划线',
        'username.length'=>'用户名字符长度为6到15之间',
        'password.require'=>'密码不得为空',
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