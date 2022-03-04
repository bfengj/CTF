<?php
/**
*	项目的
*/
class flow_projectClassModel extends flowModel
{
	
	public function initModel()
	{
		$this->workobj = m('work');
	}
	
	/**
	*	进度报告时更新对应状态
	*/
	protected function flowaddlog($a)
	{
		if($a['name']=='进度报告'){
			$arr['status'] = $a['status'];
			$this->update($arr, $this->id);
		}
	}
	
	public function flowrsreplace($rs, $lx=0){
		$zts 		= $rs['status'];
		$str 		= $this->getstatus($rs,'','',1);
		
		if($lx==2)$rs['statusstr']= $str; //列表时替换
		
		$id			= $rs['id'];
		$wwc	= $this->workobj->rows('projectid='.$id.' and `status` not in(1,5)');
		$wez	= $this->workobj->rows('projectid='.$id.'');
		if($wwc>0)$wwc='<font color=red>'.$wwc.'</font>';
		$rs['workshu'] = ''.$wwc.'/'.$wez.'';
		
		if($lx==1)$rs['status'] = $str;
		
		return $rs;
	}
	
	public function flowisreadqx()
	{
		return $this->flowgetoptmenu('shwview');
	}
	
	//显示操作菜单判断
	protected function flowgetoptmenu($num)
	{
		$fuzeid 	= $this->rs['fuzeid'];
		$runuserid 	= $this->rs['runuserid'];
		$where 		= m('admin')->gjoin($fuzeid.','.$runuserid, 'ud', $blx='where');
		$where 		= 'id='.$this->adminid.' and ('.$where.')';
		$bo 		= null;
		if($num=='shwview')$bo = true;
		if(m('admin')->rows($where)==0)$bo=false;
		return $bo;
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		
		return array(
			'where' => '',
			'order' => 'optdt desc'
		);
	}
}