<?php 
/**
	修改记录
*/
class editClassModel extends Model{
	
	public function initModel()
	{
		$this->settable('editrecord');
	}
	
	public $editarr = array();
	
	/**
	*	获取修改记录
	*	$num 对应模块编号
	*	$id id值 
	*	$oners 旧数组
	*	$newrs 新数组
	*	return string
	*/
	public function recordstr($farr,$table, $id, $oners, $newrs, $glx=1)
	{
		$str	= '';
		if(!$farr || !$oners)return $str;
		$this->editarr = array();
		$farr = $this->getfield($farr);
		$str  = $this->editcont($farr, $oners, $newrs);
		if($glx == 1 && $str != ''){
			$str  = '['.$this->adminname.']('.$this->rock->now.')修改：'.$str.'';
		}
		if($glx == 2){
			$this->addrecord($table, $id);
		}
		return $str;
	}
	
	public function recordsave($farr,$table, $id, $oners, $newrs)
	{
		$str = $this->editcont($farr, $oners, $newrs);
		$this->addrecord($table, $id);
		return $str;
	}
	
	public function editcont($farr, $oners, $narr)
	{
		$str		= '';
		$this->editarr = array();
		if($oners){
			foreach($narr as $k=>$v){
				if(!isset($farr[$k]))continue;
				$fa = $farr[$k];
				$nv = $v;
				$ov = '';
				if(isset($oners[$k]))$ov = $oners[$k];
				if($nv != $ov){
					$sel = array();
					if(isset($fa['selarr']))$sel = $fa['selarr'];
					if(isset($sel[$ov]))$ov = $sel[$ov];
					if(isset($sel[$nv]))$nv = $sel[$nv];
					$str .= ''.$fa['name'].':'.$ov.'→'.$nv.';';
					$this->editarr[] = array(
						'fieldsname'	=> $fa['name'],
						'oldval'		=> $ov,
						'newval'		=> $nv
					);
				}
			}
		}
		return $str;
	}
	
	public function addrecord($table, $id)
	{
		$editci = (int)$this->getmou('editci', "`table`='$table' and `mid`='$id'")+1;
		foreach($this->editarr as $k=>$rs){
			$rs['optid'] 	= $this->adminid;
			$rs['optname'] 	= $this->adminname;
			$rs['optdt'] 	= $this->rock->now;
			$rs['table'] 	= $table;
			$rs['mid'] 		= $id;
			$rs['editci'] 	= $editci;
			$this->insert($rs);
		}
	}
	
	/**
		获取对应表上字段信息
		$glx 0基本字段，1流程上
		return {字段名:对应信息}
	*/
	public function getfield($fieldsarr)
	{
		$farrs		= array();
		foreach($fieldsarr as $k=>$rs){
			$fid 	= $rs['fields'];
			if(substr($fid, 0, 5)=='temp_')continue;
			$farrs[$fid] = array('name' => $rs['name']);
			if(substr($rs['fieldstype'],0,6)=='change'){
				if(!$this->isempt($rs['data'])){
					$fid = $rs['data'];
					if(isset($farrs[$fid]))continue;
					$farrs[$fid] = array('name' => $rs['name'].'id');
				}
			}
		}
		return $farrs;
	}
	
}                                                                                                                                                            