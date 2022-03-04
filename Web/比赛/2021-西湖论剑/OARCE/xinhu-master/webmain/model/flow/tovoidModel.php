<?php
class flow_tovoidClassModel extends flowModel
{
	
	//展示是替换一下
	public function flowrsreplace($rs, $lx=0)
	{
		$str= '作废';
		if($rs['type']==1)$str= '删除';
		$rs['type'] = $str;
		
		//详情
		if($lx==1){
			$bilrs= $this->billmodel->getone("`sericnum`='".$rs['tonum']."'");
			if(!$bilrs){
				$rs['tonum']='<s>'.$rs['tonum'].'</s>';
				return $rs;
			}
			$mors = $this->db->getone('[Q]flow_set',"`id`='".$bilrs['modeid']."'");
			if(!$mors)return $rs;
			$url  = $this->getxiangurl($mors['num'], $bilrs['mid'], 'a');
			$rs['tonum'] = '<a href="'.$url.'">'.$rs['tonum'].'</a>';
		}
		return $rs;
	}

	//审核完成了处理单据，删除还是作废
	protected function flowcheckfinsh($zt)
	{
		if($zt!=1)return;
		$type = $this->rs['type'];
		$bilrs= $this->billmodel->getone("`sericnum`='".$this->rs['tonum']."'");
		if(!$bilrs)return;
		$mors = $this->db->getone('[Q]flow_set',"`id`='".$bilrs['modeid']."'");
		if(!$mors)return;
		//作废
		if($type==0){
			m('flow')->zuofeibill($mors['num'], $bilrs['mid'], $this->rs['explain']);
		}
		//删除
		if($type==1){
			m('flow')->deletebill($mors['num'], $bilrs['mid'], $this->rs['explain'], false);
		}
	}
	
	
}