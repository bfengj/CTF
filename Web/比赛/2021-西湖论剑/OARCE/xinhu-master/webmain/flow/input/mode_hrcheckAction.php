<?php
/**
*	此文件是流程模块【hrcheck.考核评分】对应控制器接口文件。
*/ 
class mode_hrcheckClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function pingfenAjax()
	{
		$mid  = (int)$this->post('mid');
		$flow = m('flow')->initflow('hrcheck', $mid, false);
		return $flow->pingfen();
	}
	
	public function storeafter($table, $rows)
	{
		if($this->flow->atype!='tjall')return;
		$arr = array();
		$mlen= 0;
		foreach($rows as $k=>$rs){
			$rs['trbgcolor']='#fbe5d5';
			$basr	= $this->flow->getsubdatastr($rs['id'], 1);
			$data 	= $basr['data'];
			$colums	= $basr['colums'];
			$oi 	= 0;
			foreach($colums as $k3=>$v3){
				$rs['pfval'.$oi.''] = $v3;
				$oi++;
			}
			if($oi>$mlen)$mlen = $oi;
			$rs['itemname'] = '考核内容';
			$rs['fenshu'] = '分值';
			$arr[] 	= $rs;
		
			foreach($data as $k1=>$rs1){
				$rs1['abclex'] = 'ol';
				$oi1 = 0;
				foreach($colums as $k3=>$v3){
					$rs1['pfval'.$oi1.''] = $rs1['pfzd'.$k3.''];
					$oi1++;
				}
				$arr[] = $rs1;
			}
		}
		return array(
			'rows' => $arr,
			'mlen'=> $mlen
		);
	}
}	
			