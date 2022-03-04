<?php
class mode_tovoidClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$tonum = $arr['tonum'];
		if(m($table)->rows("`id`<>$id and `tonum`='$tonum'")>0)return '此单据已申请过了';
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//读取可作废的单据是，申请日期只能是30天内的单据
	public function modebill()
	{
		$dts  = date('Y-m-d', time()-30*24*3600);
		$rows = $this->db->getrows('[Q]flow_bill',"`uid`='$this->adminid' and `status`=1 and `isdel`=0 and `applydt`>='$dts' group by `modeid`",'`modeid`,`modename`','optdt desc');
		$row  = array();
		foreach($rows as $k=>$rs){
			$row[] = array(
				'value' => $rs['modeid'],
				'name'  => $rs['modename'],
			);
		}
		return $row;
	}
	
	//获取作废单据
	public function gettonum()
	{
		$row  = array();
		if($this->rs){
			$row[] = array(
				'name' 	=> $this->rs['tonum'],
				'value' => $this->rs['tonum'],
			);
		}
		return $row;
	}
	
	/**
	*	联动获取
	*/
	public function gettonumAjax()
	{
		$modeid = (int)$this->get('modeid');
		$dts  	= date('Y-m-d', time()-30*24*3600);
		$rows 	= $this->db->getrows('[Q]flow_bill',"`uid`='$this->adminid' and `modeid`='$modeid' and `status`=1 and `isdel`=0 and `applydt`>='$dts'",'`sericnum`','optdt desc');
		$row  = array();
		foreach($rows as $k=>$rs){
			$row[] = array(
				'name'  => $rs['sericnum'],
			);
		}
		$this->returnjson($row);
	}
}	
			