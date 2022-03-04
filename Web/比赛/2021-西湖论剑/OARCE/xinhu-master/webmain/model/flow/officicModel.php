<?php
//公文查阅
class flow_officicClassModel extends flowModel
{
	public $xiangbordercolor = 'red';//默认边框颜色
	
	public function initModel()
	{
		$this->logobj = m('log');
	}
	
	//打开详情时跳转到地理位置显示
	protected function flowchangedata()
	{
		/*
		if(!isajax()){
			$num = ($this->rs['type']=='1') ? 'officib' : 'officia';
			$url = $this->getxiangurl($num, $this->id, A);
			$this->rock->location($url);
			exit();
		}*/
	}
	
	protected function flowbillwhere($uid, $lx)
	{

		//全部的
		if($lx=='all'){
			$whyere = '';
			$this->rock->setsession('officicatype','all');
		}
		return '';
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		if($lx==2){
			$zt = $this->logobj->isread($this->mtable, $rs['id'], $this->adminid);
			if($zt>0)$rs['ishui']=1;
		}
		//$rs['modenum'] = ($rs['type']=='1') ? 'officib' : 'officia';
		return $rs;
	}
	
	protected function flowdatalog($arr)
	{
		
		$arr['title'] 		= $this->moders['name'];
		
		//是否关闭查阅记录
		$arr['isgbcy'] 	= $this->moders['isgbcy'];
		if($this->rock->session('officicatype')=='all')$arr['isgbcy'] ='0'; //有权限看全部
		if($arr['isgbcy'] =='0'){
			$barr	= $this->logobj->getreadshu($this->mtable, $this->id,$this->rs['receid'] , $this->rs['optdt'], $this->adminmodel);
			$arr['readunarr'] 			= $barr['wduarr'];//读取未查阅
		}

		return $arr;
	}
	
	
}