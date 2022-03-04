<?php
/**
*	模块.印章申请
*/
class flow_sealaplClassModel extends flowModel
{
	//读取印章保管人来审批
	protected function flowcheckname($num)
	{
		if($num=='bgque'){
			$sealrs = m('seal')->getall('`id` in('.$this->rs['sealid'].')');
			if($sealrs){
				$bgid = $bgname = array();
				foreach($sealrs as $k1=>$rs1){
					$bgidaa = explode(',', $rs1['bgid']);
					$bgidab = explode(',', $rs1['bgname']);
					foreach($bgidaa as $x=>$kv){
						if(!in_array($kv, $bgid)){
							$bgid[]=$kv;
							$bgname[] = $bgidab[$x];
						}
					}
					
				}
				
				return array(join(',', $bgid), join(',', $bgname));
			}
		}
	}
	
	public function usefangshi()
	{
		$barr[] = array('value'=>'0','name'=>'盖章查看');
		$barr[] = array('value'=>'1','name'=>'外带');
		$barr[] = array('value'=>'2','name'=>'电子印章');
		return $barr;
	}
	
	//展示是替换一下
	public function flowrsreplace($rs, $lx=0)
	{
		$utype = $this->usefangshi();
		$rs['isout'] = $utype[$rs['isout']]['name'];
		if($lx==1){
			$mknum = arrvalue($rs, 'mknum');
			if(!isempt($mknum)){
				$numa = explode(',', $mknum);
				$num  = $numa[0];
				$mid  = (int)arrvalue($numa,1);
				$url  = $this->getxiangurl($num, $mid, 'auto');
				if($mid>0)$rs['mknum'] = '<a href="'.$url.'">查看对应单据详情</a>';
			}
		}
		return $rs;
	}
	
	public function inputtitle()
	{
		$tit = $this->moders['name'];
		if($this->rock->get('def_isout')=='2' || arrvalue($this->rs,'isout')=='2')$tit = '电子印章申请';
		return $tit;
	}
	
	protected function flowdatalog($arr)
	{
		
		$arr['title'] 		= $this->inputtitle();

		return $arr;
	}
}