<?php
class mode_bookborrowClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		$bookid = $arr['bookid'];
		$jydt 	= $arr['jydt'];
		if($id==0 && $jydt<$this->date)return '借阅日期不能是过去';
		$shul	= m('book')->getjieshu($bookid, $jydt, $id);
		if($shul<1)return '该书数量不够了';
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function getbookdata()
	{
		$where  = m('admin')->getcompanywhere(1);
		$rows 	= m('book')->getrows('shul>0'.$where.'','id,title,typeid','typeid');
		$arr 	= array();
		foreach($rows as $k=>$rs){
			$arr[] = array('value'=>$rs['id'],'name'=>$rs['title']);
		}
		return $arr;
	}
}	
			