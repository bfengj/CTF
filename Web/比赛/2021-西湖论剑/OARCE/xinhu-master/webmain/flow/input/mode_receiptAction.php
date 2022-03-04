<?php
/**
*	此文件是流程模块【receipt.回执确认】对应控制器接口文件。
*/ 
class mode_receiptClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$modenum = $arr['modenum'];
		$rows	 = array();
		$rows['table'] = m('flow_set')->getmou('`table`', "`num`='$modenum'");
		
		$where 	 = "`uid`='$this->adminid' and `modenum`='$modenum' and `mid`='".$arr['mid']."' and `id`<>$id";
		if($this->flow->rows($where)>0)return '你已设置这单据的回执确认了';
		
		$receid  = $arr['receid'];
		if(isempt($receid))return '没有选择需确认回执人员1';
		$receid   = $this->flow->adminmodel->gjoin($receid);
		if(isempt($receid))return '没有选择需确认回执人员2';
		
		$rows['receid']   = $receid;
		$rows['receids']  = '';
		$rows['ushuy']    = '0';
		$rows['ushuz']    = count(explode(',', $receid));
		
		return array(
			'rows' => $rows
		);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
}	
			