<?php
class inforClassAction extends Action
{

	public function publicbeforesave($table, $cans, $id)
	{
		$num = $cans['num'];
		if($num=='')$num = $this->db->ranknum($this->T($table),'num');
		return array('rows'=>array('num'=>$num));
	}
	
	public function loaddataAjax()
	{
		$id		= (int)$this->get('id');
		$data	= m('infor')->getone($id);
		$arr 	= array(
			'data'		=> $data,
			'infortype' 	=> $this->option->getdata('infortype')
		);
		echo json_encode($arr);
	}
	
	
	//导入题库提交
	public function tikuimportAjax()
	{
		$rows  	= c('html')->importdata('title,typeid,type,ana,anb,anc,and,answer,explain','title,typeid,type,ana,anb,answer');
		$oi 	= 0;
		$db 	= m('knowtiku');
		foreach($rows as $k=>$rs){
			$rs['typeid'] 	= $this->option->gettypeid('knowtikutype',$rs['typeid']);
			$rs['type']		= contain($rs['type'],'单') ? 0 : 1;
			$rs['adddt']	= $this->now;
			$rs['optdt']	= $this->now;
			$rs['optid']	= $this->adminid;
			$rs['status']	= 1;
			$db->insert($rs);
			$oi++;
		}
		backmsg('','成功导入'.$oi.'条数据');
	}
	
}