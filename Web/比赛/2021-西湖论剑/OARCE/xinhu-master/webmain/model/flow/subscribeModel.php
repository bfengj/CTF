<?php
//订阅的
class flow_subscribeClassModel extends flowModel
{
	public  $uidfields = 'optid';
	
	public function initModel()
	{
		
	}
	
	public function getstatusarr()
	{
		$barr[]	 = array('停用','#888888');
		$barr[]	 = array('启用','green');
		return $barr;
	}

	
	public function flowrsreplace($rs, $lx=0)
	{
		$ztstatus 	= $rs['status'];
		if($lx==1){
			$ztarr 			= $this->getstatusarr();
			$zta 			= $ztarr[$ztstatus];
			$rs['status']	= '<font color="'.$zta[1].'">'.$zta[0].'</font>';
		
			$ors 	= $this->remindmodel->getone($this->mwhere);
			if($ors){
				$rs['recename'] = $ors['recename'];
				$rs['ratecont'] = $ors['ratecont'];
			}
		}
		
		if($ztstatus==0 || isempt(arrvalue($rs,'ratecont')))$rs['ishui']=1;
		
		if($lx==2){
			
			unset($rs['suburl']);
			unset($rs['suburlpost']);
			if(!isempt($rs['dinguser']))$rs['optname'] = $rs['dinguser']; //订阅人
		}
		if(isset($rs['suburl'])){
			$rs['suburl'] = '<div class="wrap" style="">'.$this->rock->jm->base64decode($rs['suburl']).'</div>';
		}
		if(isset($rs['suburlpost'])){
			$rs['suburlpost'] = '<div class="wrap">'.$this->rock->jm->base64decode($rs['suburlpost']).'</div>';
		}
		return $rs;
	}
	
	protected function flowbillwhere($uid, $lx)
	{
	
		return array(
			'order'		=> 'a.id desc',
			'table'		=> '`[Q]'.$this->mtable.'` a left join `[Q]flow_remind` b on a.id=b.mid and b.`table`=\''.$this->mtable.'\'',
			'fields'	=> 'a.*,b.recename,b.ratecont,b.optname as dinguser',
			'asqom'		=> 'a.',
			'orlikefields' => 'b.recename,b.ratecont,b.optname'
		);
	}

}