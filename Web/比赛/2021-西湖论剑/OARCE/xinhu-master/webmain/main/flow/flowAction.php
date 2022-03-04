<?php
class flowClassAction extends Action
{
	public function loaddataAjax()
	{
		$id		= (int)$this->get('id');
		$data	= m('flow_set')->getone($id);
		$arr 	= array(
			'data'		=> $data
		);
		echo json_encode($arr);
	}
	
	public function modeafter($table, $rows)
	{
		return array(
			'qian' => PREFIX
		);
	}
	
	public function modebefore($table)
	{
		$where 	= '';
		$key 	= $this->post('key');
		if(!isempt($key)){
			$where = "and (`type`='$key' or `name` like '%$key%' or `table` like '$key%' or `num` like '$key%' or `sericnum` like '$key%')";
		}
		return $where;
	}
	
	private function getwherelist($setid)
	{
		return m('flow_where')->getall('setid='.$setid.'','id,name','sort');
	}
	
	public function loaddatacourseAjax()
	{
		$id		= (int)$this->get('id');
		$setid	= (int)$this->get('setid');
		$data	= m('flow_course')->getone($id);
		$arr 	= array(
			'data'		=> $data,
			'wherelist' => $this->getwherelist($setid),
			'statusstr'	=> m('flow_set')->getmou('statusstr', $setid)
		);
		echo json_encode($arr);
	}
	
	public function loaddatawhereAjax()
	{
		$id		= (int)$this->get('id');
		$data	= m('flow_where')->getone($id);
		$arr 	= array(
			'data'		=> $data,
		);
		echo json_encode($arr);
	}
	
	public function flowsetsavebefore($table, $cans)
	{
		$tab = $cans['table'];
		$tabs= trim($cans['tables']);
		$name= $this->rock->xssrepstr($cans['name']);
		$num = strtolower($cans['num']);
		$cobj= c('check');
		if(!$cobj->iszgen($tab))return '表名格式不对';
		if($cobj->isnumber($num))return '编号不能为数字';
		if(strlen($num)<4)return '编号至少要4位';
		if($cobj->isincn($num))return '编号不能包含中文';
		if(contain($num,'-'))return '编号不能有-';
		
		if($cans['isflow']>0 && isempt($cans['sericnum'])) return '有流程必须有写编号规则，请参考其他模块填写';
		$rows['num']= $this->rock->xssrepstr($num); 
		$rows['name']= $name;
		if(!isempt($tabs)){
			if($cobj->isincn($tabs))return '多行子表名不能包含中文';
			$tabsa 		= explode(',', $tabs);
			foreach($tabsa as $tabsas){
				if(isempt($tabsas))return '多行子表名('.$tabs.')不规范';
			}
		}
		$rows['tables']= $tabs;
		return array(
			'rows' => $rows
		);
	}
	
	private function setsubtsta($tabs, $alltabls, $tab, $slxbo, $ssm)
	{
		if(isempt($tabs))return;
		if(!in_array(''.PREFIX.''.$tabs.'', $alltabls)){
			$sql = "CREATE TABLE `[Q]".$tabs."` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`mid` int(11) DEFAULT '0' COMMENT '对应主表".$tab.".id',
`sort` int(11) DEFAULT '0' COMMENT '排序号',
`comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
PRIMARY KEY (`id`),KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
			$bo = $this->db->query($sql);
		}else{
			$fields = $this->db->getallfields(''.PREFIX.''.$tabs.'');
			$str 	= '';
			if(!in_array('mid', $fields))$str.=",add `mid` int(11) DEFAULT '0' COMMENT '对应主表".$tab.".id'";
			if(!in_array('sort', $fields))$str.=",add `sort` int(11) DEFAULT '0' COMMENT '排序号'";
			if(!in_array('comid', $fields))$str.=",add `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id'";
			if($slxbo && !in_array('sslx', $fields)){
				$ssma = explode(',', $ssm);
				$ss1  = '';
				foreach($ssma as $k=>$ssmas)$ss1.=','.$k.''.$ssmas.'';
				if($ss1!='')$ss1 = substr($ss1, 1);
				$str.=",add `sslx` tinyint(1) DEFAULT '0' COMMENT '".$ss1."'";
			}
			if($str!=''){
				$sql = 'alter table `'.PREFIX.''.$tabs.'` '.substr($str,1).'';
				$this->db->query($sql);
			}
		}
	}
	
	public function flowsetsaveafter($table, $cans)
	{
		$isflow = $cans['isflow'];
		$name 	= $cans['name'];
		$tab  	= $cans['table'];
		$tabs  	= $cans['tables'];
		$alltabls = array();
		//创建保存多行子表
		if(!isempt($tabs)){
			$alltabls 	= $this->db->getalltable();
			$tabsa 		= explode(',', $tabs);
			$addsts 	= array();
			foreach($tabsa as $tabsas){
				$this->setsubtsta($tabsas, $alltabls, $tab, in_array($tabsas, $addsts), $cans['names']);
				$alltabls[] = ''.PREFIX.''.$tabsas.'';
				$addsts[]	= $tabsas;
			}
		}
		
		if(isempt($tab))return;
		if(!$alltabls)$alltabls 	= $this->db->getalltable();
		if($isflow==0){
			if(!in_array(''.PREFIX.''.$tab.'', $alltabls)){
				$sql = "CREATE TABLE `[Q]".$tab."` (`id` int(11) NOT NULL AUTO_INCREMENT,`comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',`optid` int(11) DEFAULT '0' COMMENT '操作人id',`optname` varchar(20) DEFAULT NULL COMMENT '操作人',`optdt` datetime DEFAULT NULL COMMENT '操作时间',PRIMARY KEY (`id`))ENGINE=MyISAM  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='$name';";
				$bo = $this->db->query($sql);
			}else{
				$fields = $this->db->getallfields(''.PREFIX.''.$tab.'');
				$str 	= '';
				if(!in_array('comid', $fields) && !in_array('companyid', $fields))$str.=",add `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id'";
				if($str!=''){
					$sql = 'alter table `'.PREFIX.''.$tab.'` '.substr($str,1).'';
					$this->db->query($sql);
				}
			}
			return;
		}
		
		if(!in_array(''.PREFIX.''.$tab.'', $alltabls)){
			$sql = "CREATE TABLE `[Q]".$tab."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid`  int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='$name';";
			$bo = $this->db->query($sql);
		}else{
			$fields = $this->db->getallfields(''.PREFIX.''.$tab.'');
			$str 	= '';
			if(!in_array('uid', $fields))$str.=",add `uid` int(11) DEFAULT '0'";
			if(!in_array('optdt', $fields))$str.=",add `optdt` datetime DEFAULT NULL COMMENT '操作时间'";
			if(!in_array('optid', $fields))$str.=",add `optid` int(11) DEFAULT '0'";
			if(!in_array('optname', $fields))$str.=",add `optname` varchar(20) DEFAULT NULL COMMENT '操作人'";
			if(!in_array('applydt', $fields))$str.=",add `applydt` date DEFAULT NULL COMMENT '申请日期'";
			if(!in_array('explain', $fields))$str.=",add `explain` varchar(500) DEFAULT NULL COMMENT '说明'";
			if(!in_array('status', $fields))$str.=",add `status` tinyint(1) DEFAULT '1' COMMENT '状态'";
			if(!in_array('isturn', $fields))$str.=",add `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交'";
			if(!in_array('comid', $fields) && !in_array('companyid', $fields))$str.=",add `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id'";
			if($str!=''){
				$sql = 'alter table `'.PREFIX.''.$tab.'` '.substr($str,1).'';
				$this->db->query($sql);
			}
		}
	}
	
	public function elementafter($table, $rows)
	{
		$moders = m('flow_set')->getone($this->mid);
		$farrs 	= array();
		if($this->mid>0){
			$tass 	= $moders['table'];
			$tasss 	= $moders['tables'];
			$farr	= $this->db->gettablefields('[Q]'.$tass.'');
			$farrs[]= array('id'=>'','name'=>'————↓以下主表('.$tass.')的字段————');
			foreach($farr as $k=>$rs){
				$farrs[]= array('id'=>$rs['name'],'name'=>'['.$rs['name'].']'.$rs['explain'].'');
			}
			if(!isempt($tasss)){
				$tasssa = explode(',', $tasss);
				foreach($tasssa as $k=>$tasss){
					$farr	= $this->db->gettablefields('[Q]'.$tasss.'');
					$farrs[]= array('id'=>'','name'=>'————↓以下第'.($k+1).'个多行子表('.$tasss.')的字段————');
					foreach($farr as $k=>$rs){
						$farrs[]= array('id'=>$rs['name'],'name'=>'['.$rs['name'].']'.$rs['explain'].'');
					}
				}
			}
		}
		return array(
			'flowarr'=>$this->getmodearr(),
			'moders'=>$moders,
			'fieldsarr' => $farrs,
			'fieldstypearr'=> $this->option->getdata('flowinputtype')
		);
	}
	
	public function elementbefore($table)
	{
		$mid = (int)$this->post('mid');
		$this->mid = $mid;
		return array(
			'where' => 'and `mid`='.$mid.'',
			'order'	=> 'iszb,sort,id'
		);
	}
	
	//模块多模版
	public function modetpl_before($table)
	{
		$mid = (int)$this->post('mid');
		$this->mid = $mid;
		return array(
			'where' => 'and `setid`='.$mid.'',
			'order'	=> 'sort,id'
		);
	}
	public function modetpl_after($table, $rows)
	{
		return array(
			'flowarr'=>$this->getmodearr(' and `istpl`=1')
		);
	}
	public function modetpledit_before($table)
	{
		$mid = (int)$this->post('mid');
		$this->mid = $mid;
		return array(
			'where' => 'and `mid`='.$mid.' and `iszb`=0',
			'order'	=> 'sort,id'
		);
	}
	public function modetpledit_after($table, $rows)
	{
		$sid  = (int)$this->post('sid');
		$data = false;
		$fieldsluru = $fieldsbitian = '';
		if($sid>0){
			$data = m('flow_modetpl')->getone($sid);
			$fieldsluru 	= $data['fieldsluru'];
			$fieldsbitian 	= $data['fieldsbitian'];
			foreach($rows as $k=>$rs){
				if(!isempt($fieldsluru)){
					$islu = 0;
					if(contain(','.$fieldsluru.',',','.$rs['fields'].','))$islu=1;
					$rows[$k]['islu'] = $islu;
				}
				if(!isempt($fieldsbitian)){
					$isbt = 0;
					if(contain(','.$fieldsbitian.',',','.$rs['fields'].','))$isbt=1;
					$rows[$k]['isbt'] = $isbt;
				}
			}
		}
		return array(
			'data' => $data,
			'rows' => $rows,
		);
	}
	public function modetpl_savefieldsbefore($table, $cans)
	{
		$tplnum = $cans['tplnum'];
		if(c('check')->isincn($tplnum))return '编号不能包含中文';
		$id 	= (int)$this->post('id');
		if(m($table)->rows("`tplnum`='$tplnum' and `id`<>'$id'")>0)return '编号已经存在';

	}
	public function modetpl_savefieldsafter($table, $cans)
	{
		$mid = $cans['setid'];
		$tab = m('mode')->getmou('`table`',$mid);
		if(!isempt($tab)){
			$fields = $this->db->getallfields(''.PREFIX.''.$tab.'');
			$str 	= '';
			if(!in_array('mtplid', $fields))$str.=",add `mtplid` int(11) DEFAULT '0' COMMENT '对应多模版flow_modetpl.id'";
			if($str!=''){
				$sql = 'alter table `'.PREFIX.''.$tab.'` '.substr($str,1).'';
				$this->db->query($sql);
			}
		}
	}
	
	//单据操作菜单
	public function flowmenubefore($table)
	{
		$mid = (int)$this->post('mid');
		$this->mid = $mid;
		return 'and `setid`='.$mid.'';
	}
	
	public function flowmenuafter($table, $rows)
	{
		
		return array(
			'flowarr'=>$this->getmodearr()
		);
	}
	
	//条件where
	public function flowwhereafter($table, $rows)
	{
		return array(
			'flowarr'=> $this->getmodearr()
		);
	}
	public function flowwherebefore($table)
	{
		return array(
			'table' => '`[Q]'.$table.'` a left join `[Q]flow_set` b on a.setid=b.id',
			'fields'=> 'a.*,b.num as modenum,b.name as modename'
		);
	}
	
	//单据通知设置
	public function flowtodobefore($table)
	{
		$mid = (int)$this->post('mid');
		$this->mid = $mid;
		$where = '';
		if($mid>0)$where = 'and `setid`='.$mid.'';
		return array(
			'where' => $where,
			'table' => '`[Q]'.$table.'` a left join `[Q]flow_set` b on a.setid=b.id',
			'fields'=> 'a.*,b.name as modename'
		);
	}
	
	public function flowtodoafter($table, $rows)
	{
		$fielslist = m('flow_element')->getrows("mid='$this->mid' and iszb=0 and islu=1",'fields,name','sort');
		foreach($fielslist as &$v){
			$v['name'] = ''.$v['fields'].'.'.$v['name'].'';
		}
		
		$courselist	= m('flow_course')->getrows("setid='$this->mid' and `status`=1",'id,name','pid,sort');
		foreach($courselist as &$v1){
			$v1['name'] = ''.$v1['id'].'.'.$v1['name'].'';
		}
		$dbss = m('remind');
		foreach($rows as $k=>$rs){
			$whereid = '';
			if($rs['whereid']>'0')$whereid = $this->db->getmou('[Q]flow_where','name', $rs['whereid']);
			
			$rows[$k]['whereidstr'] = $whereid;
			
			if($rs['botask']=='1'){
				$rows[$k]['remindrs'] = $dbss->getremindrs('flow_todo', $rs['id']);
			}
		}
		
		return array(
			'flowarr'	=> $this->getmodearr(),
			'wherelist' => $this->getwherelist($this->mid),
			'fielslist' => $fielslist,
			'courselist' => $courselist,
			'rows'		=> $rows
		);
	}

	
	
	private function getmodearr($whe='')
	{
		return m('mode')->getmodearr($whe);
	}
	
	
	
	public function inputzsAction()
	{
		$setid	= $this->get('setid');
		$atype	= (int)$this->get('atype','0');
		$rs 	= m('flow_set')->getone("`id`='$setid'");
		if(!$rs)exit('sorry!');
		$this->smartydata['rs'] = $rs;
		$atypea = array('PC端','手机端','PC端打印');
		$this->title  = $rs['name'].'_'.$atypea[$atype].'展示页面设置';
		$fleftarr 	= m('flow_element')->getrows("`mid`='$setid' and `iszb`=0",'`fields`,`name`','`iszb`,`sort`');
		$modenum	= $rs['num'];
		$fleft[]= array('base_name', '申请人',0);
		$fleft[]= array('base_deptname', '申请部门',0);
		$fleft[]= array('base_sericnum', '单号',0);
		$fleft[] = array('file_content', '相关文件',0);
		$iszb 	= 0;
		foreach($fleftarr as $k=>$brs){
			$fleft[]= array($brs['fields'], $brs['name'], $iszb);
		}
		if(!isempt($rs['tables'])){
			$tablea = explode(',', $rs['tables']);
			$namesa = explode(',', $rs['names']);
			$fleft[]= array('', '<font color=#ff6600>↓多行子表</font>', 0);
			foreach($tablea as $k=>$rs1){
				$fleft[]= array('subdata'.$k.'', $namesa[$k], 0);
			}
		}
		if($rs['isflow']>0){
			$fleft[]= array('', '<font color=#ff6600>↓流程审核步骤</font>', 0);
			$rows 	= m('flow_course')->getrows('setid='.$setid.' and `status`=1','id,name','pid,sort');
			foreach($rows as $k=>$rs){
				$fleft[]= array('course'.$rs['id'].'_all', ''.$rs['name'].'处理意见', 0);
				$fleft[]= array('course'.$rs['id'].'_name', ''.$rs['name'].'处理人', 0);
				$fleft[]= array('course'.$rs['id'].'_zt', ''.$rs['name'].'处理状态', 0);
				$fleft[]= array('course'.$rs['id'].'_dt', ''.$rs['name'].'处理时间', 0);
				$fleft[]= array('course'.$rs['id'].'_sm', ''.$rs['name'].'处理说明', 0);
			}
		}

		
		$this->smartydata['fleft'] = $fleft;
		$this->smartydata['atype'] = $atype;

		$path 		= ''.P.'/flow/page/view_'.$modenum.'_'.$atype.'.html';
		$bianhao 	= $modenum;
		if(COMPANYNUM){
			$path1 		= ''.P.'/flow/page/view_'.$modenum.'_'.COMPANYNUM.'_'.$atype.'.html';
			if(file_exists($path1)){
				$path = $path1;
				$bianhao.='_'.COMPANYNUM.'';
			}
		}
		
		$content 	= '';
		if(file_exists($path)){
			$content = file_get_contents($path);
		}
		$this->smartydata['content'] = $content;
		$this->smartydata['bianhao'] = $bianhao;
	}
	
	
	
	public $setinputid = 0;
	public function inputAction()
	{
		$setid	= (int)$this->get('setid','0');
		if($this->setinputid>0)$setid = $this->setinputid;
		$atype	= $this->get('atype');
		
		$rs 	= m('flow_set')->getone("`id`='$setid'");
		if(!$rs)exit('sorry!');
		$rs['zibiaoshu'] = count(explode(',', $rs['tables']));
		$this->smartydata['rs'] = $rs;
		$this->title  = $rs['name'].'_录入页面设置';
		$fleftarr 	= m('flow_element')->getrows("`mid`='$setid'",'*','`iszb`,`sort`');
		$modenum	= $rs['num'];
		$fleft[]= array('base_name', '申请人',0);
		$fleft[]= array('base_deptname', '申请部门',0);
		$fleft[]= array('base_sericnum', '单号',0);
		$fleft[] = array('file_content', '相关文件',0);
		$iszb 	= 0;
		foreach($fleftarr as $k=>$brs){
			$bt='';
			if($brs['isbt']==1)$bt='*';
			$iszbs = $brs['iszb'];
			if($iszbs>0&&$iszb != $iszbs){
				$fleft[]= array('', '<font color=#ff6600>—第'.$iszbs.'个多行子表—</font>', $iszbs);
				$fleft[]= array('xuhao', '序号', $iszbs);
			}
			$iszb	= $iszbs;
			$fleft[]= array($brs['fields'], $bt.$brs['name'], $iszb);
		}

		
		$this->smartydata['fleft'] = $fleft;
		$bianhao 	= $modenum;
		$path 		= ''.P.'/flow/page/input_'.$modenum.'.html';
		if(COMPANYNUM){
			$path1 		= ''.P.'/flow/page/input_'.$modenum.'_'.COMPANYNUM.'.html';
			if(file_exists($path1)){
				$bianhao.='_'.COMPANYNUM.'';
				$path = $path1;
			}
		}
		$content 	= '';
		if(file_exists($path)){
			$content = file_get_contents($path);
		}
		$this->smartydata['bianhao'] = $bianhao;
		$this->smartydata['content'] = $content;
		$apaths		= ''.P.'/flow/input/inputjs/mode_'.$modenum.'.js';
		if(!file_exists($apaths)){
			$stra='//流程模块【'.$modenum.'.'.$rs['name'].'】下录入页面自定义js页面,初始函数
function initbodys(){
	
}';
			$this->rock->createtxt($apaths, $stra);
		}
		
		$apaths		= ''.P.'/flow/input/mode_'.$modenum.'Action.php';
		$apath		= ''.ROOT_PATH.'/'.$apaths.'';
		if(!file_exists($apath)){
			$stra = '<?php
/**
*	此文件是流程模块【'.$modenum.'.'.$rs['name'].'】对应控制器接口文件。
*/ 
class mode_'.$modenum.'ClassAction extends inputAction{
	
	/**
	*	重写函数：保存前处理，主要用于判断是否可以保存
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id 0添加时，大于0修改时
	*	$addbo Boolean 是否添加时
	*	return array(\'msg\'=>\'错误提示内容\',\'rows\'=> array()) 可返回空字符串，或者数组 rows 是可同时保存到数据库上数组
	*/
	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
	/**
	*	重写函数：保存后处理，主要保存其他表数据
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id
	*	$addbo Boolean 是否添加时
	*/	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
}	
			';
			$this->rock->createtxt($apaths, $stra);
			
		}
		if(!file_exists($apath))echo '<div style="background:red;color:white;padding:10px">无法创建文件：'.$apaths.'，会导致录入数据无法保存，请手动创建！代码内容如下：</div><div style="background:#caeccb">&lt;?php<br>class mode_'.$modenum.'ClassAction extends inputAction<br>{<br>}</div>';
	}
	
	private function geuolvstr($str)
	{
		$str = str_replace(array('<?php','$_POST','exec','system') ,'', $str);
		return $str;
	}
	
	private function geuolvstra($str)
	{
		$str = str_replace(array('../','.','/') ,'', $str);
		return $str;
	}
	
	public function pagesaveAjax()
	{
		$content = $this->geuolvstr($this->post('content'));
		$num 	 = $this->geuolvstra($this->post('num'));
		if(m('flow_set')->rows("`num`='$num'")==0)return;
		$path 	 = ''.P.'/flow/page/input_'.$num.'.html';
		if(COMPANYNUM)$path 	 = ''.P.'/flow/page/input_'.$num.'_'.COMPANYNUM.'.html';
		$bo 	 = $this->rock->createtxt($path, $content);
		if(!$bo){
			echo '无法写入文件:'.$path.'';
		}else{
			echo 'success';
		}
	}
	
	public function viewsaveAjax()
	{
		$content = $this->geuolvstr($this->post('content'));
		$num 	 = $this->geuolvstra($this->post('num'));
		if(m('flow_set')->rows("`num`='$num'")==0)return;
		$atype 	 = (int)$this->post('atype','0');
		$path 	 = ''.P.'/flow/page/view_'.$num.'_'.$atype.'.html';
		if(COMPANYNUM){
			$path 		= ''.P.'/flow/page/view_'.$num.'_'.COMPANYNUM.'_'.$atype.'.html';
		}
		
		if(isempt($content)){
			@unlink($path);
			return 'success';
		}
		$bo 	 = $this->rock->createtxt($path, $content);
		if(!$bo){
			echo '无法写入文件:'.$path.'';
		}else{
			echo 'success';
		}
	}
	
	public function getinputAjax()
	{
		$num 	 = $this->post('num');
		$path 	 = ''.P.'/flow/page/input_'.$num.'.html';
		$cont 	 = '';
		if(file_exists($path)){
			$cont = file_get_contents($path);
			$cont = str_replace('*','', $cont);
		}
		echo $cont;
	}
	
	
	
	public function getsubtableAjax()
	{
		$iszb 	= (int)$this->post('iszb');
		$hang 	= (int)$this->post('hang');
		$modeid = (int)$this->post('modeid');
		$str 	= m('input')->getsubtable($modeid, $iszb, $hang);
		if($str=='')$this->backmsg('没有设置第'.$iszb.'个多行子表');
		$this->backmsg('','ok', $str);
	}
	
	//一键布局录入页
	public $yinruonearr = false;
	public function yinruoneAjax()
	{
		
		if($this->yinruonearr){
			$modeid = $this->yinruonearr['modeid'];
			$xgwj   = (int)arrvalue($this->yinruonearr, 'xgwj');
			$base   = (int)arrvalue($this->yinruonearr, 'base');
		}else{
			$modeid = (int)$this->post('modeid');
			$xgwj   = (int)$this->post('xgwj');
			$base   = (int)$this->post('base');
		}
		
		$mrs 	= m('mode')->getone($modeid);
		$rowsa   = m('flow_element')->getall('mid='.$modeid.' and `iszb`=0 and `islu`=1','*','sort,id');
		$zhang  = array('textarea','htmlediter','uploadfile','uploadimg','changedeptusercheck');
		$s = '<table width="100%" border="0"><tbody><tr class="autoyijianview">';
		$xuo = 0;
		$yczd = '';
		$rows = array();
		foreach($rowsa as $k1=>$rs1){
			if($rs1['fieldstype']=='hidden' || $rs1['fieldstype']=='fixed'){
				$yczd.='{'.$rs1['fields'].'}';
			}else{
				$rows[] = $rs1;
			}
		}
		$zlen= count($rows)-1;
		foreach($rows as $k=>$rs){
			$xuo++;
			$name = '^'.$rs['fields'].'^';
			if($rs['isbt']=='1')$name='*'.$name.'';
			if(in_array($rs['fieldstype'], $zhang) || contain($rs['attr'],'maxhang')){
				if($xuo==2)$s.='<td height="34" align="right" class="ys1"></td><td class="ys2"></td></tr><tr>';
				$s.='<td height="34" align="right" class="ys1">'.$name.'</td><td colspan="3" class="ys2">{'.$rs['fields'].'}'.$yczd.'</td>';
				if($xuo==1)$xuo=2;
			}else{
				$s.='<td height="34" width="15%" align="right" class="ys1">'.$name.'</td><td  width="35%" class="ys2">{'.$rs['fields'].'}'.$yczd.'</td>';
			}
			$yczd='';
			if($xuo==2){
				$s.='</tr>';
				if($k<$zlen)$s.='<tr>';
				$xuo=0;
			}
			if($xuo==1 && $k==$zlen){
				$s.='<td height="34" align="right" class="ys1"></td><td class="ys2"></td></tr>';
			}
		}
		$tables	 = $mrs['tables'];
		if(!isempt($tables)){
			$tablesa = explode(',', $tables);
			$tablesn = explode(',', $mrs['names']);
			foreach($tablesa as $k=>$tab){
				$str 	= m('input')->getsubtable($modeid, $k+1, 1);
				$s.='<tr ><td class="ys2" style="background-color:#CCCCCC;" colspan="4"><strong>'.arrvalue($tablesn, $k).'</strong></td></tr>';
				$s.='<tr><td class="ys0" colspan="4">'.$str.'</td></tr>';
			}
		}
		if($xgwj==1)$s.='<td height="34" align="right" class="ys1">^file_content^</td><td colspan="3" class="ys2">	{file_content}</td>';
		if($base==1)$s.='<tr><td height="34"  align="right" class="ys1">^base_name^</td><td class="ys2" >{base_name}</td><td align="right" class="ys1" >^base_deptname^</td><td class="ys2" >{base_deptname}</td></tr>';
		$s.='</tbody></table>';
		return $s;
	}
	
	
	
	
	
	public function getmodearrAjax()
	{
		$arr = $this->getmodearr();
		$this->backmsg('','ok', $arr);
	}
	
	
	
	
	public function viewshowbefore($table)
	{
		$this->modeid = (int)$this->post('modeid');
		if($this->modeid==0){
			return 'and 1=2';
		}
		$this->moders = m('flow_set')->getone($this->modeid);
		$this->isflow = $this->moders['isflow'];
		$table = $this->moders['table'];
		$where = $this->moders['where'];
		if(!isempt($where)){
			$where = $this->rock->covexec($where);
			$where = "and $where";
		}
		return array(
			'table' => '[Q]'.$table,
			'where' => $where
		);
	}
	
	public function viewshowafter($table, $rows)
	{
		$arr 	= array();
		if($rows){
			$flow 	= m('flow')->initflow($this->moders['num']);
			$mbil 	= m('flowbill');
			foreach($rows as $k=>$rs){
				$zt 	= '';
				if(isset($rs['status']))$zt = $rs['status'];
				$narr['id'] 		= $rs['id'];
				$narr['ishui'] 		= ($zt=='5')?1:0;
				$narr['optname'] 	= arrvalue($rs,'optname');
				$narr['modenum'] 	= $this->moders['num'];
				$narr['modename'] 	= $this->moders['name'];
				$narr['table'] 		= $this->moders['table'];
				$narr['optdt'] 		= arrvalue($rs,'optdt');
				$nors 	= $flow->flowrsreplace($rs, 2);
				$narr['summary'] 	= $this->rock->reparr($this->moders['summary'], $nors);
				$otehsr = '';
				if($flow->isflow>0){
					$billrs = $flow->billmodel->getone("`table`='$flow->mtable' and `mid`='".$rs['id']."'");
					$otehsr = arrvalue($billrs, 'nowcheckname');
				}
				$narr['status']		= $flow->getstatus($rs,'',$otehsr,1);
				$narr['chushu']		= $flow->flogmodel->rows("`table`='".$flow->mtable."' and `mid`='".$rs['id']."'");
				
				$arr[] = $narr;
			}
		}
		return array('rows'=>$arr);
	}
	
	public function viewlogshowbefore($table)
	{
		$where = "and `table`='".$this->post('num')."' and `mid`='".$this->post('mid')."'";
		return array(
			'where' => $where
		);
	}
	
	//删除单据，用户=1不需要权限判断
	public function delmodeshujuAjax()
	{
		$this->modeid 	= (int)$this->post('modeid');
		$mid 			= (int)$this->post('mid');
		$modenum 		= $this->post('modenum');
		if($this->modeid>0){
			$this->moders 	= m('flow_set')->getone($this->modeid);
			if(!$this->moders)backmsg('sorry!');
			$modenum = $this->moders['num'];
		}
		if(isempt($modenum))backmsg('操作有误');
		
		$msg	= m('flow')->deletebill($modenum, $mid, '', $this->adminid!=1);
		if($msg=='ok')$msg='';
		backmsg($msg);
	}
	
	//元素保存之前判断
	public function elemensavefieldsbefore($table, $cans, $id)
	{
		$iszb 	= (int)$cans['iszb'];
		$fields = $cans['fields'];
		$type 	= $cans['fieldstype'];
		$data 	= $cans['data'];
		if(c('check')->isincn($fields))return '对应字段不能有汉字';
		if(c('check')->isnumber($fields))return '对应字段不能是数字';
		if($type=='selectdatafalse' || $type=='selectdatatrue'){
			if(isempt($data))return '此类型数据源必须设置如写：'.$fields.'store';
			if(contain(','.$data.',',',id,'))return '数据源不能包含,id';
		}
		
		$mid 	= $cans['mid'];
		$this->mmoders 	= m('flow_set')->getone($mid);
		$tablessa = explode(',', $this->mmoders['tables']);
		if($iszb>0){
			$tabsss = $this->rock->arrvalue($tablessa, $iszb-1);
			if(isempt($tabsss))return '模块没有设置第'.$iszb.'个多行子表';
		}
		if(m($table)->rows("`mid`='$mid' and `iszb`='$iszb' and `fields`='$fields' and `id`<>'$id'")>0){
			return '字段['.$fields.']已存在了';
		}
	}
	
	//保存字段判断，自动创建字段
	public function elemensavefields($table, $cans)
	{
		$fields = $cans['fields'];
		$name 	= $cans['name'];
		$mid 	= $cans['mid'];
		$type 	= $cans['fieldstype'];
		$lens 	= $cans['lens'];
		$dev 	= $cans['dev'];
		$data 	= $cans['data'];
		$iszb 	= (int)$cans['iszb'];
		$this->xiaoshu = (int)arrvalue($cans,'xiaoshu','-1');
		
		$mrs 	= $this->mmoders;
		$tables 	= $mrs['table'];
		if($iszb>0){
			$tables = '';
			$tablessa = explode(',', $mrs['tables']);
			if(isset($tablessa[$iszb-1]))$tables = $tablessa[$iszb-1];
		}
		$fiesss = substr($fields,0,5);
		if($fiesss == 'base_' || $fiesss == 'temp_')return;
		if(!isempt($tables) && $cans['islu']==1){
			$_fieldsa = $this->db->gettablefields('[Q]'.$tables.'');$allfields = array();
			foreach($_fieldsa as $k2=>$rs2)$allfields[$rs2['name']] =  $rs2;
			$this->createfields($allfields, $tables, $fields, $type, $lens, $dev, $name);
			if(substr($type,0,6)=='change' && !isempt($data)){
				if($type=='changeuser' || $type=='changedept'){
					$type='number';
				}
				$this->createfields($allfields, $tables, $data, $type, $lens, '', $name.'的ID');
			}
		}
	}
	
	//创建字段
	private function createfields($allfields, $tables, $fields, $type, $lens, $dev, $name)
	{
		if(isempt($lens))$lens='0';
		$lens = (int)$lens;
		if(!isset($allfields[$fields])){
			$str = "ALTER TABLE `[Q]".$tables."` ADD `$fields` ";
			if($type=='date' || $type=='datetime' || $type=='time'){
				$str .= ' '.$type.'';
			}else if($type=='number'){
				if($this->xiaoshu>0){
					if($lens<6)$lens = 6;
					$str .= ' decimal('.$lens.', '.$this->xiaoshu.')';
				}else if($lens>6){
					$str .= ' int('.$lens.')';
				}else{
					$str .= ' smallint(6)';
				}
			}else if($type=='checkbox'){
				$str .= ' tinyint(1)';	
			}else if($type=='textarea'){
				$str .= ' varchar(2000)';
			}else{
				if($lens=='0')$lens='50';
				$str .= ' varchar('.$lens.')';
			}
			if(!isempt($dev) && !contain($dev,'{'))$str.= " DEFAULT '$dev'";
			$str.= " COMMENT '$name'";
			$this->db->query($str);
		}else{
			$farr = $allfields[$fields];
			$ustr = '';
			$len  	= (int)$farr['lens'];
			$xslen1 = (int)arrvalue($farr,'xslen1','0');
			$xslen2 = (int)arrvalue($farr,'xslen2','0');
			
			if($farr['type']=='varchar'){
				if($lens>$len)$ustr='varchar('.$lens.')';
			}
			if($farr['type']=='smallint' || $farr['type']=='int'){
				if($lens>6 && $lens>$len)$ustr='int('.$lens.')';
			}
			
			if($farr['type']=='decimal'){
				if($lens>$xslen1)$ustr= 'decimal('.$lens.', '.$xslen2.')';
			}
			
			if($type=='date' || $type=='datetime'){
				if($farr['type']!=$type){
					$ustr= ''.$type.'';
				}
			}
			
			if($type=='number'){
				if($lens<6)$lens = $xslen1;
				if($lens<6)$lens = 6;
				if($this->xiaoshu>0){
					$ustr= 'decimal('.$lens.', '.$this->xiaoshu.')';
				}
			}
			
			if($ustr!=''){
				if(!isempt($dev) && !contain($dev,'{'))$ustr.= " DEFAULT '$dev'";
				$ustr= "ALTER TABLE `[Q]".$tables."` MODIFY column `$fields` ".$ustr." COMMENT '$name'";
				$this->db->query($ustr);
			}
		}
	}
	
	
	
	
	
	
	public function reloadpipeiAjax()
	{
		$mid 	= (int)$this->post('mid');
		$whe	= '';
		if($mid>0)$whe=' and id='.$mid.'';
		echo m('flow')->repipei($whe);
	}
	
	public function setwherelistafter($table, $rows)
	{
		$dbs = m('flow_where');
		foreach($rows as $k=>$rs){
			$shu = $dbs->rows("`setid`='".$rs['id']."'");
			if($shu>0)$rows[$k]['shu'] = $shu;
		}
		return array('rows'=>$rows);
	}
	
	public function setcourselistafter($table, $rows)
	{
		$dbs = m('flow_course');
		foreach($rows as $k=>$rs){
			$shu = $dbs->rows("`setid`='".$rs['id']."'");
			if($shu>0)$rows[$k]['shu'] = $shu;
		}
		return array('rows'=>$rows);
	}
	
	public function setcourselistbefore($table)
	{
		$where 	= '';
		$key 	= $this->post('key');
		if(!isempt($key)){
			$where = "and (`type`='$key' or `name` like '%$key%' or `table` like '$key%' or `num` like '$key%' or `sericnum` like '$key%')";
		}
		return $where;
	}
	
	
	//删除模块
	public function delmodeAjax()
	{
		$id = (int)$this->post('id','0');
		return $this->delmode($id, true);
	}
	
	private function delmode($id, $dm=false)
	{
		if($this->getsession('isadmin')!='1')return '非管理员不能操作';
		$mrs = m('flow_set')->getone($id);
		if(!$mrs)return '模块不存在';
		$num 	= $mrs['num'];
		if($num!='demo' && $mrs['type']=='系统')return '系统类型模块不能删除清空';
		$flow	= m('flow')->initflow($num);
		$table 	= $mrs['table'];
		$where 	= $mrs['where'];
		if(!isempt($where)){
			$where = $this->rock->covexec($where);
			$where = "and $where";
		}else{
			$where = '';
		}
		
		$rows  = m($table)->getrows('1=1 '.$where.'');
		foreach($rows as $k=>$rs){
			$ssid 	= $rs['id'];
			$flow->loaddata($ssid, false);
			$flow->deletebill('清空模块数据', false);
		}
		
		$name 	= $mrs['name'];
		if($dm){
			m('flow_set')->delete("`id`='$id'");
			m('flow_course')->delete("`setid`='$id'");
			m('flow_element')->delete("`mid`='$id'");
			m('flow_extent')->delete("`modeid`='$id'");
			m('flow_where')->delete("`setid`='$id'");
			m('flow_menu')->delete("`setid`='$id'");
			m('flow_todo')->delete("`setid`='$id'");
			m('flow_todos')->delete("`modenum`='$num'");
			
			m('log')->addlog('模块','删除模块['.$name.']');
		}else{
			m('log')->addlog('模块','清空模块['.$name.']的数据');
		}
		
		$this->db->query("alter table `[Q]$table` AUTO_INCREMENT=1");
		return 'ok';
	}
	
	//清空模块上数据
	public function clearallmodeAjax()
	{
		$id = (int)$this->post('id','0');
		return $this->delmode($id, false);
	}
	
	//刷新序号
	public function rexuhaoAjax()
	{
		$mid 	= (int)$this->get('modeid');
		$db 	= m('flow_element');
		
		$rows 	= $db->getall('mid='.$mid.' and iszb=0','id','sort asc,id asc');
		foreach($rows as $k=>$rs)$db->update('sort='.$k.'',$rs['id']);
		
		$rows 	= $db->getall('mid='.$mid.' and iszb=1','id','sort asc,id asc');
		foreach($rows as $k=>$rs)$db->update('sort='.$k.'',$rs['id']);
		
		$rows 	= $db->getall('mid='.$mid.' and iszb=2','id','sort asc,id asc');
		foreach($rows as $k=>$rs)$db->update('sort='.$k.'',$rs['id']);
		
		$rows 	= $db->getall('mid='.$mid.' and iszb=3','id','sort asc,id asc');
		foreach($rows as $k=>$rs)$db->update('sort='.$k.'',$rs['id']);
	}
	
	public function flowcourselistbefore($rows)
	{
		return array('order'=>'pid,sort');
	}
	
	//流程步骤显示
	public function flowcourselistafter($table, $rows)
	{
		$arr = array();$pid = -1;$maxpid = -1;
		foreach($rows as $k=>$rs){
			if($rs['pid'] != $pid){
				$recename 	= $this->rock->arrvalue($rs, 'recename');
				if(isempt($recename))$recename = '全体人员';
				$arr[] 		= array(
					'name' 	=> '流程'.($rs['pid']+1).'，适用：'.$recename.'',
					'level'	=> 1,
					'stotal'=> 1,
					'status'=> 1,
					'iszf'	=> 0,
					'id'		=> $rs['id'],
					'pid'		=> $rs['pid'],
					'sort'		=> 0,
					'recename'	=> '',
				);
			}
			$rs['level'] 	= 2;
			$rs['stotal'] 	= 0;
			$arr[] 	= $rs;
			$pid 	= $rs['pid'];
			$maxpid = $pid;
		}
		return array(
			'rows' => $arr,
			'maxpid' => $maxpid+1,
		);
	}
	
	
	
	

	
	
	//生成列表页面
	public function changeliebAjax()
	{
		$modeid = (int)$this->post('modeid');
		$path 	= m('mode')->createlistpage($modeid);
		if($path=='')$path	= '无法生成，可能没权限写入'.P.'/flow/page目录';
		echo $path;
	}
	
	//生成所有
	public function allcreateAjax()
	{
		$dbs  = m('mode');
		$rows = $dbs->getall("`status`=1");
		$oi   = 0;
		$msg  = '';
		foreach($rows as $k=>$rs){
			$path 	= $dbs->createlistpage($rs,1);
			if($path=='none')continue;
			if($path==''){
				if($path=='')$msg	= '无法生成，可能没权限写入'.P.'/flow/page目录';
				break;
			}else{
				$oi++;
			}
		}
		if($msg=='')$msg='已生成'.$oi.'个模块，可到'.P.'/flow/page下查看';
		echo $msg;
	}
	
	public function savecolunmsAjax()
	{
		$num 	= $this->post('num');
		$modeid = (int)$this->post('modeid');
		$str 	= $this->post('str');
		$this->option->setval($num.'@'.(-1*$modeid-1000), $str,'模块列定义');
		$path 	= m('mode')->createlistpage($modeid);
		$msg 	= 'ok';
		//if($path=='')$msg='已保存,但无法从新生成列表页,自定义列将不能生效';
		echo $msg;
	}
	
	
	//选择人员组
	public function getcnameAjax()
	{
		$arr = array();
		$rows = m('flow_cname')->getall("`pid`=0 and `num` is not null",'num,name','`sort`');
		foreach($rows as $k=>$rs)$arr[] = array('name'=>$rs['name'],'value'=>$rs['num']);
		return $arr;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//图形的流程管理
	public function courseflowinitAjax()
	{
		$setid = (int)$this->get('setid','0');
		
		return m('flowcourse')->getCoursedata($setid);
	}
	
	public function courseflowdelAjax()
	{
		$id = (int)$this->get('id','0');
		
		m('flowcourse')->delete($id);
	}
	public function coursesavebefore($table, $arr)
	{
		$mid 	= (int)arrvalue($arr,'mid','0');
		$setid 	= (int)arrvalue($arr,'setid','0');
		$nid 	= (int)arrvalue($arr,'nid','0');
		if($mid>0 && m($table)->rows("`setid`='$setid' and `id`='$mid'")==0)return '上级步骤ID['.$mid.']不存在';
		if($nid>0 && m($table)->rows("`setid`='$setid' and `id`='$nid'")==0)return '下级步骤ID['.$nid.']不存在';
		
		
	}
	
	public function getfieldsAjax()
	{
		$setid 	= (int)$this->get('setid','0');
		$rows  	= m('flow_element')->getrows('`mid`='.$setid.' and `iszb`=0','name,fields,data,fieldstype','`sort`');
		$arr 	= array();
		foreach($rows as $k=>$rs){
			//$arr[] = array(
			//	'name' => $rs['name'].'('.$rs['fields'].')',
			//	'value' => $rs['fields'],
			//);
			$fieldstype	= $rs['fieldstype'];
			if(in_array($fieldstype, array('changeuser','changeusercheck')) && !isempt($rs['data'])){
				$arr[] = array(
					'name' => $rs['name'].'('.$rs['data'].')',
					'value' => $rs['data'],
				);
			}
		}
		return $arr;
	}
	
	public function savebeforecname($table, $arr, $id)
	{
		$num = $arr['num'];
		$to  = m('flowcname')->rows("`id`<>'$id' and `num`='$num'");
		if($to>0)return '编号['.$num.']已存在';
	}
	
	/**
	*	复制模块
	*/
	public function copymodeAjax()
	{
		$id 	= (int)$this->post('id','0');
		$bhnu 	= strtolower(trim($this->post('name')));
		if(isempt($bhnu))return '新模块编号不能为空';
		if(is_numeric($bhnu))return '模块编号不能用数字';
		if(strlen($bhnu)<4)return '编号至少要4位';
		if(c('check')->isincn($bhnu))return '编号不能包含中文';
		
		$dbs 	= m('mode');
		if($dbs->rows("`num`='$bhnu'")>0)return '模块编号['.$bhnu.']已存在';
		$mrs 	= $dbs->getone($id);
		if(!$mrs)return '模块不存在';
		$ars 	= $mrs;
		$name	= $mrs['name'].'复制';
		$biaom	= $bhnu;
		$obha 	= $mrs['num'];
		unset($ars['id']);
		$ars['name'] = $name;
		$ars['num']  = $bhnu;
		$ars['table']= $biaom;
		$tablea[]	 = $mrs['table'];
		$tables		 = '';
		if(!isempt($ars['tables'])){
			$staba = explode(',', $ars['tables']);
			foreach($staba as $kz=>$zb1){
				$tables.=','.$biaom.'zb'.($kz+1).'';
				if(!in_array($zb1, $tablea))$tablea[]=$zb1;
			}
			$tables = substr($tables, 1);
		}
		$ars['tables'] = $tables;
		$modeid  = $dbs->insert($ars);
		
		//复制表
		foreach($tablea as $kz=>$tabs){
			$sqla 	   = $this->db->getall('show create table `[Q]'.$tabs.'`');
			$createsql = $sqla[0]['Create Table'];
			$biaom1	   = ''.PREFIX.''.$biaom.'';
			if($kz>0)$biaom1	   = ''.PREFIX.''.$biaom.'zb'.$kz.'';
			$createsql = str_replace('`'.PREFIX.''.$tabs.'`','`'.$biaom1.'`',$createsql);
			$this->db->query($createsql);
			$this->db->query('alter table `'.$biaom1.'` AUTO_INCREMENT=1');
		}
		//复制表单元素
		$db1  = m('flow_element');
		$rows = $db1->getall('mid='.$id.'');
		foreach($rows as $k1=>$rs1){
			$rs2 = $rs1;
			unset($rs2['id']);
			$rs2['mid'] = $modeid;
			$db1->insert($rs2);
		}
		//复制相关布局文件
		$hurs = $this->getfiles();
		
		foreach($hurs as $k=>$file){
			$from = str_replace('{bh}',$obha,$file);
			$to   = str_replace('{bh}',$bhnu,$file);
			if(file_exists($from)){
				if($k<=1){
					$fstr = file_get_contents($from);
					if($k==0)$fstr = str_replace('flow_'.$obha.'ClassModel','flow_'.$bhnu.'ClassModel',$fstr);
					if($k==1)$fstr = str_replace('mode_'.$obha.'ClassAction','mode_'.$bhnu.'ClassAction',$fstr);
					$this->rock->createtxt($to, $fstr);
				}else{
					@copy($from, $to);
				}
			}
		}
		
		echo 'ok';
	}
	
	public function getfiles()
	{
		$hurs[] = ''.P.'/model/flow/{bh}Model.php'; //模块接口文件
		$hurs[] = ''.P.'/flow/input/mode_{bh}Action.php'; //模块控制器
		$hurs[] = ''.P.'/flow/input/inputjs/mode_{bh}.js'; //模块录入js文件
		$hurs[] = ''.P.'/flow/page/input_{bh}.html'; //PC录入模版
		$hurs[] = ''.P.'/flow/page/view_{bh}_0.html'; //PC展示模版
		$hurs[] = ''.P.'/flow/page/view_{bh}_1.html'; //手机展示模版
		$hurs[] = ''.P.'/flow/page/view_{bh}_2.html'; //打印布局
		$hurs[] = ''.P.'/flow/page/viewpage_{bh}.html'; //子模版展示
		$hurs[] = ''.P.'/flow/page/viewpage_{bh}_0.html';//子模版PC展示
		$hurs[] = ''.P.'/flow/page/viewpage_{bh}_1.html';//子模版手机展示
		return $hurs;
	}
	
	
	public function loadmodeinfoAjax()
	{
		if(getconfig('systype')=='demo')return returnerror('演示不要操作');
		$sid = $this->get('sid');
		$rows = m('flow_set')->getall('`id` in('.$sid.')','*','sort asc');
		$ids  = '';
		$mname= '';
		$table= '';
		$file = '';
		$hurs = $this->getfiles();
		$hurs[] = ''.P.'/flow/page/rock_page_{bh}.php';
		foreach($rows as $k=>$rs){
			$ids.=','.$rs['id'].'';
			$table.=','.$rs['table'].'';
			if(!isempt($rs['tables']))$table.=','.$rs['tables'].'';
			$mname.=''.$rs['name'].'('.$rs['num'].') &nbsp;';
			
			foreach($hurs as $k=>$wj){
				$wjs = str_replace('{bh}',$rs['num'],$wj);
				if(file_exists($wjs))$file.=','.$wjs.'';
			}
		}
		if($ids)$ids  	= substr($ids,1);
		if($table)$table  = substr($table,1);
		if($file)$file  = substr($file,1);
		$barr['mode']  = $ids;
		$barr['mname'] = $mname;
		$barr['table'] = $table;
		$barr['file'] = $file;
		
		return returnsuccess($barr);
	}
	
	public function loadoteinAjax()
	{
		if(getconfig('systype')=='demo')return returnerror('演示不要操作');
		$lx  = $this->post('lx');
		$sid = $this->post('sid');
		$barr = array();
		$stsa = explode(',', $sid);
		if($lx==1){
			$alltabls 	= $this->db->getalltable();
			foreach($stsa as $tab){
				if(!in_array(''.PREFIX.$tab.'', $alltabls))return returnerror(''.$tab.'表不存在');
			}
			$barr['table'] = $sid;
		}
		if($lx==2){
			foreach($stsa as $tab)if(!file_exists($tab))return returnerror(''.$tab.'文件不存在');
			$barr['file'] = $sid;
		}
		
		if($lx==3){
			$rows = m('menu')->getall('`id` in('.$sid.') and `status`=1');
			$ids = '';
			$mname= '';
			foreach($rows as $k=>$rs){
				$ids.=','.$rs['id'].'';
				$mname.=''.$rs['name'].'('.$rs['url'].') &nbsp;';
			}
			if($ids){
				$barr['menu'] = substr($ids,1);
				$barr['menu_str'] = $mname;
			}
		}
		
		if($lx==4){
			$rows = m('im_group')->getall('`id` in('.$sid.') and `valid`=1 and `type`=2');
			$ids = '';
			$mname= '';
			$fstr = '';
			foreach($rows as $k=>$rs){
				$ids.=','.$rs['id'].'';
				$mname.='<img src="'.$rs['face'].'" align="absmiddle" width="20px" height="20px">'.$rs['name'].' &nbsp;';
				$fstr.=','.$rs['face'].'';
				
				$fled = 'webmain/we/ying/yingyong/'.$rs['num'].'.html';
				if(file_exists($fled))$fstr.=','.$fled.'';
				$fled = 'webmain/we/ying/yingyong/'.$rs['num'].'.js';
				if(file_exists($fled))$fstr.=','.$fled.'';
				$fled = 'webmain/we/ying/yingyong/ying_'.$rs['num'].'Class.php';
				if(file_exists($fled))$fstr.=','.$fled.'';
				$fled = 'webmain/model/agent/'.$rs['num'].'Model.php';
				if(file_exists($fled))$fstr.=','.$fled.'';
			}
			if($ids){
				$barr['agent'] = substr($ids,1);
				$barr['agent_str'] = $mname;
			}
			if($fstr)$barr['file'] = substr($fstr,1);
		}
		
		return returnsuccess($barr);
	}
	
	public function createinstseAjax()
	{
		if(!class_exists('ZipArchive'))return returnerror('没有zip扩展无法使用');
		$name = $this->post('name');
		if(!$name)$name=TITLE.'_生成包';
		$signstr = '';
		$str = "<?php
//安装包的配置文件		
return array(
	'name' => '$name', //名称
	'ver' => '".$this->post('ver')."', //版本
	'minver'=>'".VERSION."',
	'zuozhe' => '".$this->post('zuozhe')."', //作者
	'explain' => '".$this->post('explain')."', //说明
	'updatedt'=> '$this->now', //时间
	'signstr' => '$signstr', //这个是签名
);";
		$path = ''.UPDIR.'/logs/xhazbao_'.time().'';
		$this->rock->createtxt(''.$path.'/installconfig/xinhuoa_config.php', $str);
		
		//复制文件
		$file = $this->post('file');
		if($file){
			$filea = explode(',', $file);
			foreach($filea as $fid1){
				if(file_exists($fid1)){
					$this->rock->createdir($path.'/'.$fid1);
					copy(ROOT_PATH.'/'.$fid1, ROOT_PATH.'/'.$path.'/'.$fid1);
				}
			}
		}
		
		$data 	= array();
		$modeid = $this->post('mode');
		$menuid = $this->post('menu');
		$tabless = $this->post('table');
		$agentid = $this->post('agent');
		if($menuid){
			$rows = $this->db->getall("select * from `[Q]menu` where id in($menuid)");
			$data['menu'] = $this->shangxiajich($rows,'pid');
		}
		
		if($modeid){
			//创建模块文件
			$mode = $this->db->getall("select * from `[Q]flow_set` where `id` in($modeid)");
			$cdata= array();
			foreach($mode as $k=>$rs){
				$id = $rs['id'];
				
				//元素
				$flow_element = $this->db->getall("select * from `[Q]flow_element` where mid='$id'");
				
				
				//权限
				$flow_extent = $this->db->getall("select * from `[Q]flow_extent` where modeid='$id'");
				
				
				//单据操作菜单
				$flow_menu = $this->db->getall("select * from `[Q]flow_menu` where setid='$id'");
				
				
				//模块条件
				$flow_where = $this->db->getall("select * from `[Q]flow_where` where setid='$id'");
				
				
				//审核步骤，有上下级关系
				$flow_courses = $this->db->getall("select * from `[Q]flow_course` where setid='$id'");
				$flow_course = $this->shangxiajich($flow_courses,'mid');
				
				
				//单据通知设置
				$flow_todo = $this->db->getall("select * from `[Q]flow_todo` where setid='$id'");
				
				//unset($rs['id']);
				if($rs['isflow']>2)$rs['isflow']='1';
				$cdata[$rs['num']] = array(
					'flow_set'		=> $rs,
					'flow_element' 	=> $flow_element,
					'flow_extent' 	=> $flow_extent,
					'flow_menu' 	=> $flow_menu,
					'flow_where' 	=> $flow_where,
					'flow_course' 	=> $flow_course,
					'flow_todo' 	=> $flow_todo,
				);
			}
			
			$data['mode'] = $cdata;
		}
		
		//应用的数据
		if($agentid){
			$yyrows 	  = $this->db->getall("select * from `[Q]im_group` where valid=1 and type=2 and id in($agentid)");
			$yydata 	  = array();
			foreach($yyrows as $k=>$rs){
				$menu 	  = $this->db->getall("select * from `[Q]im_menu` where mid='".$rs['id']."'");
				$yydata[] = array(
					'data' => $rs,
					'menu' => $this->shangxiajich($menu,'pid', 'menusub')
				);
			}
			$data['yydata']= $yydata;
		}
		
		if($data){
			$this->rock->createtxt($path.'/installconfig/xinhuoa_data.json', json_encode($data));
		}
		
		//数据库
		if($tabless){
			$data = array();
			$yaotable = explode(',', $tabless);
			foreach($yaotable as $tabs){
				$fields	= $this->db->gettablefields(PREFIX.$tabs);
				$shwdat = array(
					'fields' 	=> $fields,
				);
				
				$sqla 	   = $this->db->getall('show create table `'.PREFIX.$tabs.'`');
				$createsql = $sqla[0]['Create Table'];
				$crse 		= explode('ENGINE', $createsql);
				$createsql  = $crse[0].'ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
				$shwdat['createsql'] = str_replace('`'.PREFIX.$tabs.'`','`[Q]'.$tabs.'`', $createsql);
				
				$data[$tabs] = $shwdat;
			}
			if($data)$this->rock->createtxt($path.'/installconfig/xinhuoa_mysql.json', json_encode($data));
		}
		
		
		$topath = UPDIR.'/logs/xinhuoa_install_'.time().'.zip';
		$this->rock->createtxt($topath, '');
		c('zip')->packzip($path, $topath);
		
		return returnsuccess('生成成功，点我<a href="'.$topath.'">下载</a>。');
	}
	
	//上下级处理
	public function shangxiajich($rows, $fid, $ds='children')
	{
		$this->rsxiada = array();
		$sarrr = array();
		foreach($rows as $k=>$rs){
			$children = $this->shangxiajichs($rows, $fid, $rs['id']);
			if($children)$rs[$ds] = $children;
			$sarrr[]= $rs;
		}
		$barr = array();
		foreach($sarrr as $k=>$rs){
			if(!isset($this->rsxiada[$rs['id']]))$barr[] = $rs;
		}
		return $barr; 
	}
	public function shangxiajichs($rows, $fid, $pid)
	{
		$arr = array();
		foreach($rows as $k=>$rs){
			if($rs[$fid]==$pid){
				$this->rsxiada[$rs['id']] = $rs['id'];
				$children = $this->shangxiajichs($rows, $fid, $rs['id']);
				if($children)$rs['children'] = $children;
				$arr[] = $rs;
			}
		}
		return $arr;
	}
}