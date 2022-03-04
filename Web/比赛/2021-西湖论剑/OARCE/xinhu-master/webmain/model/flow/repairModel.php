<?php
//维修报备
class flow_repairClassModel extends flowModel
{
	public function initModel()
	{
		$this->iswxarr		 = c('array')->strtoarray('否|#888888,是|#ff6600');
	}
	

	
	public function flowrsreplace($rs, $lx=0)
	{
		if(isset($rs['iswx'])){
			$zt 	= $this->iswxarr[$rs['iswx']];
			$rs['iswx']	= '<font color="'.$zt[1].'">'.$zt[0].'</font>';
		}
		
		if($this->rock->arrvalue($rs,'money','0')==0)$rs['money']='';
		return $rs;
	}
	
	
}