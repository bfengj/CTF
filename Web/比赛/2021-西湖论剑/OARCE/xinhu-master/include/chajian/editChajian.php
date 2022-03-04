<?php 
/**
	修改记录插件
*/
class editChajian extends Chajian{
	
	public $editarr = array();
	
	/**
		获取修改记录
		$table 表
		$id id值
		$oners 旧数组
		$newrs 新数组
		return string
	*/
	public function record($table, $id, $oners, $newrs, $glx=1)
	{
		$str	= '';
		$db 	= m($table);
		$this->editarr = array();
		if($oners){
			$farr = $this->getfield($table);
			$str  = $this->editcont($farr, $oners, $newrs);
		}
		if($glx == 1 && $str != ''){
			$str  = '['.$this->adminname.']('.$this->rock->now.')修改：'.$str.'';
		}
		if($glx == 2){
			$this->addrecord($table, $id);
		}
		return $str;
	}
	
	public function records($farr,$table, $id, $oners, $newrs)
	{
		$this->editcont($farr, $oners, $newrs);
		$this->addrecord($table, $id);
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
		$dbs = m('editrecord');
		foreach($this->editarr as $k=>$rs){
			$rs['optid'] 	= $this->adminid;
			$rs['optname'] 	= $this->adminname;
			$rs['optdt'] 	= $this->rock->now;
			$rs['table'] 	= $table;
			$rs['mid'] 		= $id;
			$dbs->insert($rs);
		}
	}
	
	/**
		获取对应表上字段信息
		$glx 0基本字段，1流程上
		return {字段名:对应信息}
	*/
	public function getfield($table, $glx=0)
	{
		$farr	= $this->db->gettablefields($this->rock->T($table));
		$rows 	= array();
		$arrar	= c('array');
		foreach($farr as $k=>$rs){
			$va = $rs['explain'];
			$vn = $rs['name'];
			$sel= array();
			if(!$this->rock->isempt($va)){
				$vas = explode('@', $va);
				$va	 = $vas[0];
				$len = count($vas);
				if($len>1)$sel = $arrar->strtoobject($vas[1]);
				if($len>2&&$glx==1&&$vas[2]=='not')$va='';
			}
			if(!$this->rock->isempt($va)){
				$rows[$vn] = array(
					'name' 		=> $va,
					'selarr' 	=> $sel
				);
			}
		}
		return $rows;
	}
	
}                                                                                                                                                            