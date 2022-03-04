<?php
 
class mode_workClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$distid = arrvalue($arr,'distid');
		$dist   = arrvalue($arr,'dist');
		$this->oharr = array();
		if(!isempt($distid)){
			if(contain($distid,'u') || contain($distid,'d') || contain($distid,'g'))return '分配给人员必须使用多选或者单选';
			$jscj 	= (int)$this->post('temp_cjall','0');
			if($jscj==1 && contain($distid,',')){
				$distida = explode(',', $distid);
				$dista   = explode(',', $dist);
				if(count($distida)>1){
					foreach($distida as $k1=>$v1){
						if($k1>0){
							$this->oharr[] = array(
								'distid' => $distida[$k1],
								'dist' 	 => $dista[$k1],
							);
						}
					}
					
					$rows = array();
					$rows['distid'] = $distida[0];
					$rows['dist'] 	= $dista[0];
					return array(
						'rows' => $rows
					);
				}
			}
		}
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		if($this->oharr){
			$sysisturn		= (int)$this->post('istrun','1');
			$subna = '';
			if($sysisturn==0)$subna='保存';
			foreach($this->oharr as $k1=>$rs1){
				$arr['distid'] 	= $rs1['distid'];
				$arr['dist'] 	= $rs1['dist'];
				$arr['zhuid'] 	= $id;
				unset($arr['id']);
				$nid = m($table)->insert($arr);
				
				$this->flow->loaddata($nid, false);
				$this->flow->submit($subna);
			}
		}
	}
	
	public function projectdata()
	{
		$rows 	= m('project')->getall('id>0 and status in(0,3)','`id`,`type`,`title`,`progress`','optdt desc');
		$arr	= array();
		foreach($rows as $k=>$rs){
			$arr[] = array(
				'name' => '['.$rs['type'].']'.$rs['title'].'('.$rs['progress'].'%)',
				'value' => $rs['id']
			);
		}
		return $arr;
	}
}	
			