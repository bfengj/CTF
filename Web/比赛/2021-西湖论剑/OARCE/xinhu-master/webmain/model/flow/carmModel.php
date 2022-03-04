<?php
//车辆管理模块接口
class flow_carmClassModel extends flowModel
{
	public function initModel()
	{
		$this->statearr = c('array')->strtoarray('blue|办理中,green|可用,red|维修中,gray|报废');
		$this->publiarr = c('array')->strtoarray('gray|否,green|是');
		
		$this->carms	= m('carms');
	}
	
	public function flowrsreplace($rs)
	{
		if(isset($this->statearr[$rs['state']])){
			$b 			 = $this->statearr[$rs['state']];
			$rs['state'] = '<font color="'.$b[0].'">'.$b[1].'</font>';
		}
		$b 			 	= $this->publiarr[$rs['ispublic']];
		$rs['ispublic'] = '<font color="'.$b[0].'">'.$b[1].'</font>';
		$rs['djshu']	= $this->carms->rows('carid='.$rs['id'].'');
		return $rs;
	}

	
}