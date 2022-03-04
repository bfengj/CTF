<?php
class carmClassAction extends Action
{
	public function carmuserbefore($table)
	{
		return array(
			'where'  => 'and status=1 and ispublic=1',
			'fields' => 'id,carnum,cartype'
		);
	}
	
	
	public function carmuserafter($table, $rows)
	{
		$dtobj 		= c('date');
		$startdt	= $this->post('startdt', $this->date);
		$enddt		= $this->post('enddt');
		if($enddt=='')$enddt = $dtobj->adddate($startdt,'d',7);
		$jg 		= $dtobj->datediff('d',$startdt, $enddt);
		if($jg>10)$jg = 10;
		
		$data 			= m('carmrese')->getall("`status` in(0,1) and `isturn`=1 and `startdt`<='$enddt 23:59:59' and `enddt`>='$startdt' order by `startdt` asc",'`usename`,`status`,`startdt`,`enddt`,`carid`');
		foreach($data as $k=>$rs){
			$dts1 		= substr($rs['startdt'],0,10);
			$dts2 		= substr($rs['enddt'],0,10);
			$jg1 		= $dtobj->datediff('d',$dts1, $dts2);
			$dtsa 		= '';
			for($i=0; $i<=$jg1; $i++){
				if($i>0)$dts1 = $dtobj->adddate($dts1,'d',1);
				$dtsa.='['.$dts1.']';
			}
			$data[$k]['dtlist'] = $dtsa;
			
			$stz = '<font color=blue>待审核</font>';
			if($rs['status']==1)$stz = '<font color=green>已审核</font>';
			$str =''.$rs['usename'].'使用('.$stz.'):'.substr($rs['startdt'],5,11).'→'.substr($rs['enddt'],5,11).'';
			
			$data[$k]['str'] = $str;
		}
		
		$columns 		= array();
		$dt = $startdt;
		for($i=0; $i<=$jg; $i++){
			if($i>0)$dt = $dtobj->adddate($dt,'d',1);
			$key 	= 'dt'.$i.'';
			foreach($rows as $k1=>$rs1){
				$str = '';
				foreach($data as $k=>$rs){
					if(contain($rs['dtlist'],'['.$dt.']') && $rs1['id']==$rs['carid']){
						$str.=''.$rs['str'].'<br>';
					}
				}
				$rows[$k1][$key] = $str;
			}
			$week 		= $dtobj->cnweek($dt);
			$sstr 		= substr($dt,5).'('.$week.')';
			$columns[]  = $sstr;
		}
		
		$arr['rows'] 	= $rows;
		$arr['columns'] = $columns;
		$arr['startdt'] = $startdt;
		$arr['enddt'] 	= $enddt;
		return $arr;
	}
}