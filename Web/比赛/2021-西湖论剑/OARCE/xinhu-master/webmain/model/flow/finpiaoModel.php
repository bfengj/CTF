<?php
//发票管理
class flow_finpiaoClassModel extends flowModel
{
	
	public $defaultorder	= 'opendt,desc';
	
	public function flowrsreplace($rs, $lx=0)
	{
		
		return $rs;
	}
	

	
	protected function flowbillwhere($uid, $lx)
	{
		$month	= $this->rock->post('month');
		$where 	= '';
		if($month!=''){
			$where.=" and `opendt` like '$month%'";
		}

		return array(
			'where' => $where
		);
	}
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		$barr[] = array(
			'type' 		=> '收到的发票',
			'ptype' 		=> '增值税普通发票',
			'kainame' 		=> '张三',
			'money' 		=> '50',
			'custname' 		=> '销售单位',
			'maicustname' 	=> '购买单位',
			'daima' 		=> '',
			'haoma' 		=> '',
			'opendt' 		=> '2017-01-17',
			'explain' 		=> '说明',
		);
		
		$barr[] = array(
			'type' 		=> '开出去的发票',
			'ptype' 		=> '增值税普通发票',
			'kainame' 		=> '张三',
			'money' 		=> '500',
			'custname' 		=> '销售单位',
			'maicustname' 	=> '购买单位',
			'daima' 		=> '',
			'haoma' 		=> '',
			'opendt' 		=> '2017-01-17',
			'explain' 		=> '说明客户买了啥给他开了发票',
		);
		return $barr;
	}
	
	//导入之前处理，必须添加客户
	public function flowdaorubefore($rows)
	{
		$inarr	= array();
		$crmdb  = m('crm');
		foreach($rows as $k=>$rs){
			
			$arr 		= $rs;
			$custname	= $rs['custname'];
			$custrs 	= $crmdb->getcustomer($custname);
			if(!$custrs)return '行'.($k+1).'销售方名称【'.$custname.'】不存在，请先建客户档案';
			$arr['custid'] = $custrs['id'];
			
			$maicustname	= $rs['maicustname'];
			$custrs1 		= $crmdb->getcustomer($maicustname);
			if(!$custrs1)return '行'.($k+1).'购买方名称【'.$maicustname.'】不存在，请先建客户档案';
			$arr['maicustid'] = $custrs1['id'];
			
			
			$type = 1; //收到的发票
			if(contain($rs['type'],'开出去'))$type=0;//开出去发票
			$arr['type'] 	= $type;
			$arr['status'] 	= 1;
			$inarr[] = $arr;
		}
		return $inarr;
	}
}