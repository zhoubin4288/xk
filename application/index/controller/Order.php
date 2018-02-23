<?php

namespace app\index\controller;
use app\index\model\Order as OrderModel;
use think\Cache;
use think\Controller;

class Order extends Controller
{
	
    public function lst(){

       //缓存获取数据
        $cachedata=Cache::get('cachedata');
        if($cachedata){
            echo "走了缓存";
            $data=$cachedata;
        }else{
            $data=OrderModel::all();  
            //设置缓存文件 
            Cache::set('cachedata',$data);
            echo "没有走缓存";
        }
    	        
    	if(!$data){
    		echo '数据获取失败！';
    	}

    	$this->assign('data',$data);
    	return $this->fetch();
    }

    public function add(){

       if($this->request->post('dosubmit')){

       	   $data=$this->request->post();
	       $data['status']=0;      
	       $validate= validate('Order');
	       if(!$validate->check($data)){
	       	  $this->error('验证不成功！');
	       }
	       $this->insertOrder($data);
       }

          $this->view->engine->layout(true);
          return $this->fetch();       
    }
    
    public function del(){

          $id=$this->request->param('id');
          Order::destroy($id);
    } 


	//下订单status=0 未支付
	public function order(){

		$id=$this->request->param('id');				
		$findOne=OrderModel::get($id); 
		if($findOne){
			$this->assign('findOne',$findOne);
		    return $this->fetch();
		}

	}


    //已支付satus=1  未确认
    public function orderPay(){

        $id=$this->request->param('id');

        echo $id;
        //回调函数而进行分析对应时$out_trade_on
        $this->updateOrderStatus($id,1,'lst','支付');
        
        //数据库管理系统操作时，
		$findOne=OrderModel::get($id);

		if($findOne){
			$this->assign('findOne',$findOne);
			return $this->fetch();
		}
		
    }


    //交易成功satus=2    交易成功
    public function orderCorm(){
    	 $id=$this->request->param('id');
         $this->updateOrderStatus($id,2,'lst','交易');

        //数据库管理系统时。
		$findOne=OrderModel::get($id);
		if($findOne){
			$this->assign('findOne',$findOne);
			return $this->fetch();
		}
		
    }

    
    private function updateOrderStatus($id,$status,$action,$msg){
      
        $data=[
           'id'=>$id,
           "status"=>$status
        ]; 

        $re=OrderModel::where('id=:id',['id'=>$id])->update($data);
        
        if($re){
        	$this->success($msg.'成功！',"Order/$action?id=$id");
        }else{
        	$this->error($msg."失败！");
        }

    }


    private function insertOrder($data){
         $re=OrderModel::create($data,true);
         if($re){

         	//获取最后id值
         	$id=$re->id;         	
         	$this->success('下单成功','Order/order?id='.$id);
         }else{
         	$this->error('下单失败！');
         }
    }

}