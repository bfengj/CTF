<?php
/**
*	此文件是流程模块【assetm.固定资产】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_assetm|input','flow')调用到对应方法
*/ 
class mode_assetmClassAction extends inputAction{
	
	public function getfilenumAjax()
	{
		$typeid	= (int)$this->post('type');
		$onrs	= $this->option->getone($typeid);
		$val 	= arrvalue($onrs, 'value');
		
		if(isempt($val)){
			$val= strtoupper(c('pingyin')->get(arrvalue($onrs, 'name'),2));//没有设置值用拼音
		}
		
		$num 	= ''.$val.'-';
		return $this->db->sericnum($num,'[Q]assetm','num', 3);
	}
	
}	
			