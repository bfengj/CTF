<?php
//客户录入
class mode_customerClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		$name = $arr['name'];
		m('custfina')->update("`custname`='$name'", "`custid`='$id'");
		m('custract')->update("`custname`='$name'", "`custid`='$id'");
		m('custsale')->update("`custname`='$name'", "`custid`='$id'");
		m('custappy')->update("`custname`='$name'", "`custid`='$id'");
		m('goodm')->update("`custname`='$name'", "`custid`='$id' and `type` in(1,2)");//1采购,2销售
	}
	
	
	public function getothernrAjax()
	{
		$custid = (int)$this->get('custid','0');//客户id
		$ind  	= (int)$this->get('ind','0');//第几个选择卡
		$bh   	= 'custsale';//销售机会的
		$atype  = 'all'; //流程模块条件编号
		if($ind==2)$bh='custract';
		if($ind==3){
			$bh='custfina';
			$atype = 'allskd'; //所有收款单的
		}
		if($ind==4){
			$bh='custfina';
			$atype = 'allfkd';//所有付款单！
		}
		if($ind==5){
			$bh='custxiao';
		}
		if($ind==6){
			$bh='custplan';
		}
			
		//读取数据
		$flow  = m('flow')->initflow($bh);//初始化模块
		//调用方法getrowstable是在webmain\model\flow\flow.php 里面的，
		//第一个参数，流程模块条件的编号，如果没有这个编号是读取不到数据
		//第二个参数，额外添加的条件，下面那说明的跟这个客户有关
		//第3个参数，默认读取的条数，默认是100
		$cont  = $flow->getrowstable($atype, 'and `custid`='.$custid.'');//读取表格数据
		return $cont;
	}
	
	public function shatetoAjax()
	{
		$sna  = $this->post('sna');
		$sid  = c('check')->onlynumber($this->post('sid'));
		$khid = c('check')->onlynumber($this->post('khid'));
		
		m('customer')->update(array(
			'shate' 	=> $sna,
			'shateid' 	=> $sid,
		),"`id` in($khid)");
	}
}