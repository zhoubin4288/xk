<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }


    public function qingqiu(){
       
        // $url="http://www.mining120.com/news/show-htm-itemid-312724.html";
        // $url="http://mp.weixin.qq.com/s/plk9UpI04S_RFt8OB9sYUA";
        // $data=[
        //   'openid'=>'onwZV0fx07_C2jne9DiJ-xJT5jCk'
        // ];
        $url="https://www.toutiao.com/i6508522464966869508/";
    	for($i=0;$i<1000;$i++){
    		$this->_requestGet($url);
    	}
    }
     

    //http接口传输数据相关的操作处理，
    protected function https_request($url,$data = null)
		{
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			if(!empty($data))
			{
				curl_setopt($ch,CURLOPT_POST,1);//模拟POST
				curl_setopt($ch,CURLOPT_POSTFIELDS,$data);//POST内容
			}
			$outopt = curl_exec($ch);
			curl_close($ch);
			$outoptArr = json_decode($outopt,true);
			if(is_array($outoptArr))
			{
				return $outoptArr;
			}
			else
			{
				return $outopt;
			}
		}

	private function _requestGet($url, $ssl=true) {
		// curl完成
		$curl = curl_init();

		//设置curl选项
		curl_setopt($curl, CURLOPT_URL, $url);//URL
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '
Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//user_agent，请求代理信息
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);//referer头，请求来源
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);//设置超时时间

		//SSL相关
		if ($ssl) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//禁用后cURL将终止从服务端进行验证
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//检查服务器SSL证书中是否存在一个公用名(common name)。
		}
		curl_setopt($curl, CURLOPT_HEADER, false);//是否处理响应头
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_exec()是否返回响应结果

		// 发出请求
		$response = curl_exec($curl);
		if (false === $response) {
			echo '<br>', curl_error($curl), '<br>';
			return false;
		}
		curl_close($curl);
		return $response;
	}	
}
