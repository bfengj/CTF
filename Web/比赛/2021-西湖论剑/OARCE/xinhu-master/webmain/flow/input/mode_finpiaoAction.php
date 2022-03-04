<?php
/**
*	此文件是流程模块【finpiao.发票管理】对应控制器接口文件。
*/ 
class mode_finpiaoClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//发票统计
	public function piaototalAjax()
	{
		$rows = array();
		$dt1  = $this->post('dt1');
		$dt2  = $this->post('dt2');
		if(isempt($dt1))$dt1 = date('Y-01');
		if(isempt($dt2))$dt2 = date('Y-m');
		$dtobj = c('date');
		$start = $dt1.'-01';
		$enddt = $dtobj->getenddt($dt2);
		$jg    = $dtobj->datediff('m', $dt1.'-01', $dt2.'-01');
		
		$where  = m('admin')->getcompanywhere(1);
		$drows  = m('finpiao')->getall("`opendt`>='$start' and `opendt`<='$enddt' and `status`=1 ".$where."");
		$toarr	= array();
		foreach($drows as $k=>$rs){
			$rq = substr($rs['opendt'],0,7);
			if(!isset($toarr[$rq]))$toarr[$rq] = array('moneyshou'=>0,'moneykai'=>0,'moneyzong'=>0);
			$fee = floatval($rs['money']);
			//收到的
			if($rs['type']=='1'){
				$toarr[$rq]['moneyshou']+=$fee;
			}else{
				$toarr[$rq]['moneykai']+=$fee;
			}
		}
		$month = $dt2;
		$hjrows= array('moneyshou'=>0,'month' => '合计','moneykai'=>0,'moneyzong'=>0);
		for($i=0;$i<=$jg; $i++){
			if($i>0){
				$month = $dtobj->adddate($dt2.'-01','m',0-$i,'Y-m');
			}
			
			$jers 	= array('moneyshou'=>0,'month' => $month,'moneykai'=>0,'moneyzong'=>0);
			if(isset($toarr[$month])){
				$jers1 = $toarr[$month];
				$jers['moneyshou'] = $jers1['moneyshou'];
				$jers['moneykai'] = $jers1['moneykai'];
				$jers['moneyzong'] = $jers1['moneykai']-$jers1['moneyshou'];
			}
			$hjrows['moneyshou']+=$jers['moneyshou'];
			$hjrows['moneykai']+=$jers['moneykai'];
			$hjrows['moneyzong']+=$jers['moneyzong'];
			
			if($jers['moneyshou']==0)$jers['moneyshou']='';
			if($jers['moneykai']==0)$jers['moneykai']='';
			if($jers['moneyzong']==0)$jers['moneyzong']='';
			
			$rows[] = $jers;
		}
		
		if($hjrows['moneyshou']==0)$hjrows['moneyshou']='';
		if($hjrows['moneykai']==0)$hjrows['moneykai']='';
		if($hjrows['moneyzong']==0)$hjrows['moneyzong']='';
		
		$rows[] = $hjrows;
		$barr = array(
			'rows' => $rows,
			'dt1'  => $dt1,
			'dt2'  => $dt2,
			'jg'  => $jg,
			'totalCount'=>count($rows),
		);
		if($this->post('execldown')=='true')return $this->exceldown($barr); //导出
		
		return $barr;
	}
	
	/**
	*	发邮件给客户
	*/
	public function sendemailAjax()
	{
		$id = (int)$this->get('id','0');
		$rs = m('finpiao')->getone('`id`='.$id.'');
		if(!$rs || $rs['type']!=0)return returnerror('不存在');
		
		$mairs = m('customer')->getone('`id`='.$rs['maicustid'].'');
		if(!$mairs)return returnerror('客户不存在');
		$email = $mairs['email'];
		if(isempt($email))return returnerror('此客户没有设置邮箱');
		
		$fjar  = m('file')->getfilepath('finpiao', $id);
		$html  = c('html')->createtable(array(
			'custname' => '开票方',
			'maicustname' => '购买方',
			'opendt' => '开票日期',
			'ptype' => '发票类型',
			'daima' => '发票代码',
			'haoma' => '发票号码',
			'money' => '金额',
		), $rs,'发票信息');
		
		$emcont  = '您好：<br>这是我们开的发票消息请查收。<br>'.$html.'';
		if($fjar[2])$emcont.='<br><b>相关文件：</b><br>'.$fjar[2].'';
		$emcont .= '<hr>发送人：'.$rs['optname'].'<br>发送时间：'.$this->now.'';

		$msg 	= m('email')->sendmail('给您开的发票信息',$emcont , array(
			'receemail' => $email,
			'recename' 	=> $rs['maicustname'],
			'receid' 	=> $mairs['id'],
		), array(), 0, array(
			'attachpath'=> $fjar[0],
			'attachname'=> $fjar[1],
		));
		
		if($msg!='ok')return returnerror($msg);
		
		return returnsuccess('已发送给：'.$email.'');
	}
}	
			