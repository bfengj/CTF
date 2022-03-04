<?php
class cityClassAction extends Action
{
	//数据源
	public function getdataAjax()
	{
		$id 	= $this->post('id');
		if(isempt($id))$id='1';
		$id 	= (int)$id;
		$dbs 	= m('city');
		$rows 	= $dbs->getrows('pid='.$id.'','*','`sort`,`id`');
		$patha	= $dbs->getpath($id);
		$type 	= $dbs->getmou('type', $id);
		if(isempt($type))$type=-1;
		$ntype	= (int)$type+1;
		if($ntype==0)$id = 0; //顶级
		return array(
			'type' => $ntype,
			'pid'  => $id,
			'rows' => $rows,
			'path' => $patha,
		);
	}
	
	//删除
	public function deldataAjax()
	{
		$id 	= (int)$this->post('id','0');
		$to 	= m('city')->rows("`pid`='$id'");
		if($to>0)return returnerror('有'.$to.'条下级不能删除');
		m('city')->delete($id);
		return returnsuccess();
	}
	
	//从官网获取城市数据
	public function initdataAjax()
	{
		c('cache')->del('cityalldata');
		return m('city')->daorudata();
	}
	
	public function clearchaheAjax()
	{
		c('cache')->del('cityalldata');
	}
}