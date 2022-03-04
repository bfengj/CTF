<?php
/**
	文档协作
*/
class agent_wordxieClassModel extends agentModel
{
	
	protected function agentrows($rows, $rowd, $uid){
		foreach($rowd as $k=>$rs){
			$rows[$k]['fileid'] = $rs['fileid'];
			if($rs['xiebool']){
				$rows[$k]['statuscolor'] = 'green';
				$rows[$k]['statustext'] = '协作';
			}else{
				$rows[$k]['statuscolor'] = 'gray';
				$rows[$k]['statustext'] = '只读';
			}
		}
		return $rows;
	}
	
}