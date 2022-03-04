<?php 
class kaoqinClassAction extends apiAction
{
	public function adddkjlAction()
	{
		$mac 	= $this->post('mac');
		$ip 	= $this->post('ip');
		$msg 	= m('kaoqin')->adddkjl($this->adminid,0,'',$ip,$mac);
		if($msg!='')$this->showreturn('', $msg, 201);
		$this->showreturn($this->now);
	}
	
	public function getshangAction()
	{
		$kq 	= m('kaoqin');
		$dt		= $this->rock->date;
		$sbarr 	= $kq->getsbanay($this->adminid, $dt);
		$dkarr 	= $kq->getdkjl($this->adminid, $dt);
		$barr['sbarr'] = $sbarr;
		$barr['dkarr'] = $dkarr;
		
		$this->showreturn($barr);
	}
	
	public function getpaibAction()
	{
		$barr 	= array();
		$month 	= $this->get('month');
		$uid 	= $this->get('uid', $this->adminid);
		
		$maxjg	= c('date')->getmaxdt($month);
		$kqobj  = m('kaoqin');
		$ztarr	= array();
		for($i=1;$i<=$maxjg;$i++){
			$oi  	= ($i<10) ? '0'.$i.'' : $i;
			$dt 	= $month.'-'.$oi;
			$zt 	= '';
			$iswork = $kqobj->isworkdt($uid, $dt);
			if($iswork==1){
				$zt = $kqobj->getdistid($uid, $dt);
				if(!in_array($zt, $ztarr))$ztarr[] = $zt;
			}
			$barr[$dt] = $zt;
		}
		$abc = '其中：';
		if($ztarr){
			$rows = m('kqsjgz')->getall('id in('.join(',', $ztarr).')');
			foreach($rows as $k=>$rs)$abc.='['.$rs['id'].']'.$rs['name'].';';
		}
		$barr['abc'] = $abc;
		
		$this->showreturn($barr);
	}
}