<?php
class flow_receiptClassModel extends flowModel
{
	public function flowrsreplace($rs, $slx=0)
	{
		$rs['modenumshow'] = $rs['modenum'];
		$rs['modenum'] = '';
		$fte = '<font color=red>停用</font>';
		if($rs['status']=='1'){
			$fte = '<font color=green>启用</font>';
		}
		$rs['status'] = $fte;
		$rs['ishui']=0;
		
		//判断是否已确认
		if($slx==2){
			$lx 	= $this->atype;
			if($lx=='my' || $lx=='myall'){
				if(contain(','.$rs['receids'].',',','.$this->adminid.','))$rs['ishui']=1;
			}
		}
		
		return $rs;
	}
	
	public function getweiwhere($uid, $lx)
	{
		$where = '';
		if($lx=='my' || $lx=='myall'){
			$where = ' and '.$this->rock->dbinstr('receid', $uid).''; //需要我回执
			
			//为确认
			if($lx=='my'){
				$where .= ' and (not '.$this->rock->dbinstr('receids', $uid).' or `receids` is null)';
			}
		}
		return $where;
	}
	
	//未确认统计
	public function getweitotal($uid)
	{
		$where = $this->getweiwhere($uid, 'my');
		$to  = $this->rows('`status`=1 '.$where.'');
		return $to;
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$where = $this->getweiwhere($uid, $lx);
		return $where;
	}
	
	
	protected function flowsubmit($na, $sm)
	{
		if($this->rs['status']==1)$this->tisongtodo();
	}
	
	//审核完成后发通知
	protected function flowcheckfinsh($zt)
	{
		if($zt==1)$this->tisongtodo();
	}
	
	//推送提醒
	private function tisongtodo()
	{
		$this->pushs($this->rs['receid'], "模块：{modename}\n发送人：{optname}\n内容：{explain}",'单据回执确认', array(
			'modenum' => $this->rs['modenum'],
			'id' => $this->rs['mid'],
		));
	}
}