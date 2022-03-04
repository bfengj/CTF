<?php
/**
*	考勤统计
*/
class agent_kqtotalClassModel extends agentModel
{
	protected function agentdata($uid, $lx)
	{
		$key 	= $this->rock->post('key');
		$month	= date('Y-m');
		if($lx=='mylast' || $lx=='downlast')$month = c('date')->adddate($month.'-01','m', -1,'Y-m');
		$mdts	= m('kaoqin');
		$drows	= $rows =  array();
		if(contain($lx,'down') || !isempt($key)){
			$whre1  = $mdts->admindb->monthuwhere($month);
			$where	= $mdts->admindb->getdowns($uid, 1).$whre1;
			if(!isempt($key))$where.=$mdts->admindb->getkeywhere($key);
			$drows	= $mdts->admindb->getrows($where, '`id`,`name`,`workdate`,`quitdt`','`sort`');
		}else{
			$drows[]= array('id'=>$uid,'name'=>'我');
		}
		
		
		foreach($drows as $k=>$rs){
			$cont 	= '';
			
			$rwnk = $this->rock->arrvalue($rs, 'workdate');
			if(!isempt($rwnk))$cont.='<font color=#888888>入职日期：</font>'.$rwnk.'<br>';
			
			$rwnk = $this->rock->arrvalue($rs, 'quitdt');
			if(!isempt($rwnk))$cont.='<font color=#888888>离职日期：</font>'.$rwnk.'<br>';
			
			$carr 	= $mdts->alltotal($month, $rs['id']);
			foreach($carr['fields'] as $k=>$v){
				$v1 = $this->rock->arrvalue($carr['data'], $v);
				$u1 = $this->rock->arrvalue($carr['unita'], $v,'次');
				if(!isempt($v1)){
					$cont.='<font color=#888888>'.$k.'：</font>'.$v1.'('.$u1.')<br>';
				}
			}
	
			$rows[] = array(
				'title' => ''.$rs['name'].'['.$month.']统计',
				'cont'	=> $cont,
				'month'	=> $month,
				'uid'	=> $rs['id']
			);
		}
		
		$arr['rows'] 	= $rows;
		return $arr;
	}
}