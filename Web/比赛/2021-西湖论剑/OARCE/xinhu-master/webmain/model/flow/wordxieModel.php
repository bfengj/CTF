<?php
//文档协作
class flow_wordxieClassModel extends flowModel
{


	public function flowrsreplace($rs, $lx=0)
	{
		$rs['xiebool'] = false;
		$xienameid		= $rs['xienameid'];
		if($rs['optid']==$this->adminid){
			$rs['xiebool'] = true;
		}else if(!isempt($xienameid)){
			if($this->adminmodel->containjoin($xienameid, $this->adminid))
				$rs['xiebool'] = true;
		}
		$str = '';
		if(arrvalue($rs,'isgk')=='1')$str='<font color=green>可做模版</font>';
		$rs['isgk'] = $str;
		return $rs;
	}
	
	protected function flowgetoptmenu($num)
	{	
		//判断是否可协作
		if($num=='editfile'){
			$rs = $this->flowrsreplace($this->rs,0);
			if($rs['xiebool']){
				return array('officebj'=>getconfig('officebj')); //返回编辑方式
			}else{
				return false;
			}
		}
	}
	
	/**
	*	协作好了上传文件
	*/
	public function savefile($fileid)
	{
		$fobj  	  = m('file');
		$filename = ''.$this->rs['name'].'.'.$this->rs['wtype'].'';
		//把原来的文件名修改
		$ofrs = $fobj->getone($this->rs['fileid']);
		if($ofrs){
			$ofilename = ''.$this->rs['name'].'_'.$ofrs['optname'].'_'.date('YmdHis', strtotime($ofrs['adddt'])).'.'.$this->rs['wtype'].'';
			$fobj->update(array(
				'filename' => $ofilename
			),$this->rs['fileid']);
		}
		
		$this->update(array(
			'fileid' => $fileid
		), $this->id);
		$fobj->update(array(
			'filename' 	=> $filename,
			'mid' 		=> $this->id,
			'mtype' 	=> $this->mtable,
			'mknum' 	=> $this->modenum,
		),$fileid);
		
		$this->addlog(array(
			'name' => '上传文件'
		));
		//通知给对应人
		
		return 'ok';
	}
	
	//在线编辑
	public function floweditoffice($frs, $ofrs)
	{
		
	}
}