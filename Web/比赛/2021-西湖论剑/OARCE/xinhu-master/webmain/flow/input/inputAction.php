<?php
class inputAction extends Action
{
	public $mid = 0;
	public $flow;
	public $rs 	= array();
	
	public function initAction()
	{
		/*
		$aid 	= (int)$this->get('adminid');
		$token 	= $this->get('token');
		$uid 	= m('login')->autologin($aid, $token);
		$this->getlogin();
		$this->loginnot();*/
	}
	
	private $fieldarr = array();
	private $ismobile = 0;
	
	protected $moders = array();
	
	
	//保存前处理，主要用于判断是否可以保存
	protected function savebefore($table,$arr, $id, $addbo){}
	
	//保存后处理，主要用于判断是否可以保存
	protected function saveafter($table,$arr, $id, $addbo){}
	
	//生成列表页，数据读取前处理
	protected function storebefore($table){}
	
	//生成列表页，数据读取后处理
	protected function storeafter($table, $rows){}
	
	//过滤html代码
	private function xxsstolt($uaarr)
	{
		foreach($uaarr as $k=>$v){
			$vss = strtolower($v);
			if(contain($vss, '<script')){
				$uaarr[$k] = str_replace(array('<','>'),array('&lt;','&gt;'), $v);
			}
		}
		return $uaarr;
	}
	
	/**
	*	录入的保存
	*/
	public function saveAjax()
	{
		$id				= (int)$this->request('id');
		$modenum		= $this->request('sysmodenum');
		$uid			= $this->adminid;
		$this->flow		= m('flow')->initflow($modenum);
		$this->moders	= $this->flow->moders;
		$modeid			= $this->moders['id'];
		$isflow			= (int)$this->moders['isflow'];
		$flownum		= $this->moders['num'];
		$table			= $this->moders['table'];
		$sysisturn		= (int)$this->post('istrun','1');
		$this->checkobj	= c('check');
		if($this->isempt($table))$this->backmsg('模块未设置表名');
		
		$fieldsarr		= array();
		$fiexar			= $this->flow->flowfieldarr($this->flow->fieldsarra, 2);
		foreach($fiexar as $k2=>$rs2)if($rs2['islu']==1)$fieldsarr[]=$rs2;
		if(!$fieldsarr)$this->backmsg('没有录入元素');
		
		$db	   = m($table);$subna = '提交';$addbo = false;$where = "`id`='$id'"; $oldrs = false;
		$this->mdb = $db;
		
		if($id==0){
			$where = '';
			$addbo = true;
			$isadd = m('view')->isadd($modeid, $uid);
			if(!$isadd)$this->backmsg('无权添加['.$this->moders['name'].']的数据1;');
		}else{
			$oldrs = $db->getone($id);
			if(!$oldrs)$this->backmsg('记录不存在');
			
			$this->flow->loaddata($id);
			if(!$this->flow->iseditqx())
				$this->backmsg('无权编辑['.$this->moders['name'].']的数据;');
			
			if($isflow>0){
				if($oldrs['uid']==$uid || $oldrs['optid']==$uid || $this->flow->floweditother){
				}else{
					//$this->backmsg('不是你提交/申请的单据，不允许编辑');
				}
				if($oldrs['status']==1)$this->backmsg('单据已审核完成，不允许编辑');
			}
			$subna = '编辑';
		}
		if($oldrs)$this->rs = $oldrs;
		$uaarr  = $farrs 	= array();
		$lvls	= array('text','textarea','ditumap');
		foreach($fieldsarr as $k=>$rs){
			$fid = $rs['fields'];
			$fi1 = substr($fid, 0, 5);
			if($fi1=='temp_' || $fi1=='base_')continue;
			$val = $this->post($fid);
			if($rs['isbt']==1 && isempt($val))$this->backmsg(''.$rs['name'].'不能为空');
			$msy = $this->attrcheck($val, arrvalue($rs, 'attr'), $this->checkobj);
			if($msy)$this->backmsg(''.$rs['name'].''.$msy.'');
			if(in_array($rs['fieldstype'], $lvls))$val = htmlspecialchars($val);
			$uaarr[$fid] = $val;
			$farrs[$fid] = array('name' => $rs['name']);
		}
		
		//人员选择保存的
		foreach($fieldsarr as $k=>$rs){
			if(substr($rs['fieldstype'],0,6)=='change'){
				if(!$this->isempt($rs['data'])){
					$fid = $rs['data'];
					if(isset($uaarr[$fid]))continue;
					$val = $this->post($fid);
					if($rs['isbt']==1&&$this->isempt($val))$this->backmsg(''.$rs['name'].'id不能为空');
					$uaarr[$fid] = $val;
					$farrs[$fid] = array('name' => $rs['name'].'id');
				}
			}
			if($rs['fieldstype']=='num'){
				$fid = $rs['fields'];
				if($this->flow->rows("`$fid`='{$uaarr[$fid]}' and `id`<>$id")>0)$uaarr[$fid]=$this->flow->createbianhao($rs['data'], $fid);
			}
			if($rs['fieldstype']=='uploadfile'){
				$_val= arrvalue($uaarr, $rs['fields']);
				if(!isempt($_val))$this->otherfileid.=','.$_val.'';
			}
		}
		
		
		
		//默认字段保存
		$allfields = $this->db->getallfields('[Q]'.$table.'');
		if(in_array('optdt', $allfields))$uaarr['optdt'] = $this->now;
		if(in_array('optid', $allfields))$uaarr['optid'] = $this->adminid;
		if(in_array('optname', $allfields))$uaarr['optname'] = $this->adminname;
		
		if(in_array('uid', $allfields) && $addbo){
			$uaarr['uid'] = $this->post('uid', $this->adminid);
		}
		if(isset($uaarr['uid'])){
			$urs = $this->flow->adminmodel->getone($uaarr['uid']);
			in_array('applyname', $allfields) and $uaarr['applyname'] = $urs['name'];
			in_array('applydeptname', $allfields) and $uaarr['applydeptname'] = $urs['deptname'];
		}
		
		if(in_array('applydt', $allfields) && $id==0)
			$uaarr['applydt'] = $this->post('applydt', $this->date);
		if($addbo){
			if(in_array('createdt', $allfields))$uaarr['createdt'] = $this->now;
			if(in_array('adddt', $allfields))$uaarr['adddt'] = $this->now;
			if(in_array('createid', $allfields))$uaarr['createid'] = $this->adminid;
			if(in_array('createname', $allfields))$uaarr['createname'] = $this->adminname;
		}
		
		//保存公司的
		if(in_array('comid', $allfields)){
			if($addbo)$uaarr['comid'] = m('admin')->getcompanyid();
			if(arrvalue($oldrs,'comid')=='0')$uaarr['comid'] = m('admin')->getcompanyid();
		}
		
		if($isflow>0){
			$uaarr['status']= '0';
			if($sysisturn==0){
				$uaarr['isturn']= '0';
				$subna = '保存';
			}
		}else{
			if(in_array('status', $allfields))$uaarr['status'] = (int)$this->post('status', '1');
			if(in_array('isturn', $allfields))$uaarr['isturn'] = (int)$this->post('isturn', '1');
		}
		
		//保存条件的判断
		foreach($fieldsarr as $k=>$rs){
			$ss  = '';
			if(isset($uaarr[$rs['fields']]))$ss = $this->flow->savedatastr($uaarr[$rs['fields']], $rs, $uaarr);
			if($ss!='')$this->backmsg($ss);
		}
		
		//判断保存前的
		$ss 	= '';
		$befa 	= $this->savebefore($table, $this->getsavenarr($uaarr, $oldrs), $id, $addbo);
		$notsave= array();//不保存的字段
		if(is_string($befa)){
			$ss = $befa;
		}else{
			if(isset($befa['msg']))$ss=$befa['msg'];
			if(isset($befa['rows'])){
				if(is_array($befa['rows']))foreach($befa['rows'] as $bk=>$bv)$uaarr[$bk]=$bv;
			}
			if(isset($befa['notsave'])){
				$notsave=$befa['notsave'];
				if(is_string($notsave))$notsave = explode(',', $notsave);
			}
		}
		if(!$this->isempt($ss))$this->backmsg($ss);
		
		//不保存字段过滤掉
		if(is_array($notsave))foreach($notsave as $nofild)if(isset($uaarr[$nofild]))unset($uaarr[$nofild]);
		
		$uaarr	= $this->xxsstolt($uaarr);//过滤特殊文字
		
		foreach($uaarr as $kf=>$kv){
			if(!in_array($kf, $allfields)){
				$this->backmsg('模块主表['.$this->flow->mtable.']上字段['.$kf.']不存在');
			}
		}
		
		//isonly唯一值的判断
		foreach($fieldsarr as $k=>$rs){
			$fiesd  = $rs['fields'];
			if($rs['isonly']=='1' && isset($uaarr[$fiesd])){
				$dval = $uaarr[$fiesd];
				if(!isempt($dval)){
					if($db->rows("`id`<>'$id' and `$fiesd`='$dval'")>0)$this->backmsg(''.$rs['name'].'['.$dval.']已存在了');
				}
			}
		}
		
		//判断子表的
		$tabless	 = $this->moders['tables'];
		$tablessa	 = array();
		if(!isempt($tabless))$tablessa = explode(',', $tabless);
		if($tablessa)foreach($tablessa as $zbx=>$zbtab){
			if($zbtab){
				$this->getsubtabledata($zbx);
				if($this->subtabledata_msg)$this->backmsg($this->subtabledata_msg);
			}
		}
		
		
		$bo = $db->record($uaarr, $where);;
		if(!$bo)$this->backmsg($this->db->lasterror());
		
		if($id==0)$id = $this->db->insert_id();
		m('file')->addfile($this->post('fileid'), $table, $id, $modenum);
		if($this->otherfileid!='')m('file')->addfile(substr($this->otherfileid,1), '', $id, $modenum);
		$newrs 	= $db->getone($id);
		$this->companyid 	= isset($newrs['companyid']) ? (int)$newrs['companyid'] : (int)arrvalue($newrs, 'comid', '0');
		if($this->companyid==0)$this->companyid = m('admin')->getcompanyid();
		
		//保存多行子表
		if($tablessa)foreach($tablessa as $zbx=>$zbtab){
			if($zbtab)$this->savesubtable($zbtab, $id, $zbx, $addbo);
		}
	
		
		//保存后处理
		$this->saveafter($table,$this->getsavenarr($uaarr, $oldrs), $id, $addbo);
		
		//保存修改记录
		$editcont = '';
		if($oldrs){
			$newrs 		= $db->getone($id);
			$editcont 	= m('edit')->recordsave($farrs, $table, $id, $oldrs, $newrs);
		}
		$msg 	= '';
		$this->flow->editcont = $editcont;
		$this->flow->loaddata($id, false);
		$this->flow->submit($subna);
		
		$this->backmsg('', $subna, $id);
	}
	
	//格式的判断
	private function attrcheck($val, $attr, $checkobj)
	{
		if(!isempt($val) && !isempt($attr)){
			if(contain($attr, 'email') && !$checkobj->isemail($val))return '必须是邮箱格式';
			if(contain($attr, 'mobile') && !$checkobj->iscnmobile($val))return '必须是11位手机号';
			if(contain($attr, 'onlyen') && $checkobj->isincn($val))return '不能有中文';
			if(contain($attr, 'onlycn') && !$checkobj->isincn($val))return '必须包含中文';
			if(contain($attr, 'number') && !$checkobj->isnumber($val))return '必须是数字';
			if(contain($attr, 'date') && !$checkobj->isdate($val))return '必须是日期格式如2020-02-02';
		}
		return '';
	}
	
	private function getsavenarr($nsrr, $bos=false)
	{
		if(!is_array($bos))$bos = array();
		if($nsrr)foreach($nsrr as $k=>$v)$bos[$k]=$v;
		return $bos;
	}
	
	private $subtabledata = array();
	private $subtabledata_msg = '';
	private $otherfileid  = '';
	public function getsubtabledata($xu)
	{
		$this->subtabledata_msg = '';
		if(isset($this->subtabledata[$xu]))return $this->subtabledata[$xu];
		$arr 	= array();
		$oi 	= (int)$this->post('sub_totals'.$xu.'');
		if($oi<=0)return $arr;
		$modeid		= $this->moders['id'];
		$iszb		= $xu+1;
		$farr		= m('flow_element')->getrows("`mid`='$modeid' and `islu`=1 and `iszb`=$iszb",'`name`,`fields`,`isbt`,`fieldstype`,`savewhere`,`dev`,`data`,`attr`','`sort`');
		$sort 		= 0;
		for($i=0; $i<$oi; $i++){
			$sid  = (int)$this->post('sid'.$xu.'_'.$i.'');
			$bos  = true;
			$uaarr['id'] = $sid;
			foreach($farr as $k=>$rs){
				$fid= $rs['fields'];
				$flx= $rs['fieldstype'];
				if(substr($fid,0,5)=='temp_')continue;
				$na = ''.$fid.''.$xu.'_'.$i.'';
				if(!isset($_POST[$na]))$bos=false;
				if($bos){
					$val= $this->post($na);
					if($rs['isbt']==1 && isempt($val))$bos=false;
				}
				if($bos){
					$msy = $this->attrcheck($val,$rs['attr'], $this->checkobj);
					if($msy){
						$msy='第'.$iszb.'子表行'.($sort+1).'的'.$rs['name'].''.$msy.'';
						$this->subtabledata_msg = $msy;
						return $arr;
					}
					$uaarr[$fid] = $val;
					if(substr($flx,0,6)=='change' && !isempt($rs['data'])){
						$na = ''.$rs['data'].''.$xu.'_'.$i.'';
						$val= $this->post($na);
						$uaarr[$rs['data']] = $val;
					}
					if($flx=='uploadfile'){
						if($val)$this->otherfileid.=','.$val.'';
					}
				}
				if(!$bos)break;
			}
			if(!$bos)continue;
			$uaarr['sort'] 	= $sort;
			$sort++;
			$arr[] = $uaarr;
		}
		$this->subtabledata[$xu] = $arr;
		return $arr;
	}
	
	//多行子表的保存
	private function savesubtable($tables, $mid, $xu, $addbo)
	{
		$dbs 		= m($tables);
		$data 		= $this->getsubtabledata($xu);
		$len 		= count($data);
		$idss		= '0';
		$whes 		= '';

		$allfields 	= $this->db->getallfields('[Q]'.$tables.'');
		$oarray 	= array();
		if(in_array('optdt', $allfields))$oarray['optdt'] 		= $this->now;
		if(in_array('optid', $allfields))$oarray['optid'] 		= $this->adminid;
		if(in_array('optname', $allfields))$oarray['optname'] 	= $this->adminname;
		if(in_array('uid', $allfields) && $addbo)$oarray['uid'] = $this->post('uid', $this->adminid);
		if(in_array('applydt', $allfields) && $addbo)$oarray['applydt']	= $this->post('applydt', $this->date);
		if(in_array('status', $allfields))$oarray['status']		= 0;
		if(in_array('sslx', $allfields)){
			$oarray['sslx']	= $xu;
			$whes			= ' and `sslx`='.$xu.'';
		}
		
		if(in_array('comid', $allfields))$oarray['comid'] 		= $this->companyid;
		
		if($data)foreach($data as $k=>$uaarr){
			$sid 			= $uaarr['id'];
			$where			= "`id`='$sid'";
			$uaarr['mid'] 	= $mid;
			if($sid==0)$where = '';
			foreach($oarray as $k1=>$v1)$uaarr[$k1]=$v1;
			if($k==0){
				foreach($uaarr as $k2=>$v2){
					if(!in_array($k2, $allfields)){
						$this->backmsg('第'.($xu+1).'个子表['.$tables.']上字段['.$k2.']不存在');
						break;
					}
				}
			}
			unset($uaarr['id']);
			$dbs->record($uaarr, $where);
			if($sid==0)$sid = $this->db->insert_id();
			$idss.=','.$sid.'';
		}
		$delwhere = "`mid`='$mid'".$whes." and `id` not in($idss)";
		$dbs->delete($delwhere);
	}
	
	//获取数据
	public function getdataAjax()
	{
		$flownum = $this->request('flownum');
		$id		 = (int)$this->request('mid');
		$arr 	 = m('flow')->getdataedit($flownum, $id);
		$this->backmsg('', '', $arr);
	}
	
	
	public function lumAction()
	{
		$ybarr	 = $this->option->authercheck();
		if(is_string($ybarr))return $ybarr;
		$this->ismobile = 1;
		$this->luactions();
	}
	
	public function luAction()
	{
		$this->ismobile = 0;
		$this->luactions();
	}
	
	public function lumoAction()
	{
		$ybarr	 = $this->option->authercheck();
		if(is_string($ybarr))return $ybarr;
		$this->ismobile = 1;
		$this->luactions();
	}

	public function lusAction()
	{
		$this->ismobile = 1;
		$menuid	= (int)$this->get('menuid');
		$fields 	= m('flow_menu')->getmou('fields', $menuid);
		if(isempt($fields))exit('sorry;');
		$fields	= str_replace(',',"','", $fields);
		$stwhe	= "and `fields` in('$fields')";
		$this->luactions(1, $stwhe);
	}

	//高级搜索显示框
	public function highsouAction()
	{
		$this->displayfile = ''.P.'/flow/input/tpl_input_lus.html';
		$this->ismobile = 1;
		$this->luactions(0, '', 1);
	}
	
	//$lutype=1高级搜索用的
	private function luactions($slx=0, $stwhe='', $lutype=0)
	{
		$this->tpltype = 'html';
		$uid		= $this->adminid;
		$num		= $this->jm->gettoken('num');
		$mid		= (int)$this->jm->gettoken('mid');
		$this->mid  = $mid;
		$this->rs   = array();
		$this->flow = m('flow')->initflow($num);
		$moders		= $this->flow->moders;
		$modename 	= $moders['name'];
		if($moders['status']=='0')exit('模块['.$modename.']已停用了;');
		
		$apaths		= ''.P.'/flow/input/mode_'.$moders['num'].'Action.php';
		if(!file_exists($apaths))exit('没有创建布局录入页面，无法打开，请到【流程模块→表单元素管理】下的点按钮“PC端录入页面布局”，更多查看'.c('xinhu')->helpstr('bwb3mf').'。');
		$isflow		= (int)$moders['isflow'];
		$this->smartydata['moders']	= array(
			'num' 	=> $moders['num'],
			'id' 	=> $moders['id'],
			'name' 	=> $moders['name'],
			'names' => $moders['names'],
			'isflow'=> $isflow,
			'iscs'	=> $moders['iscs'],
			'isbxs'	=> $moders['isbxs'],
		);
		$this->smartydata['chao']	= $this->flow->getcsname($mid);
		$modeid 	= $moders['id'];
		if($mid==0 && $lutype==0){
			$isadd = m('view')->isadd($modeid, $uid);
			if(!$isadd)exit('无权添加['.$modename.']的数据，请到[流程模块→流程模块权限]下添加权限');
		}
		
		$content 	= '';
		$oldrs 		= m($moders['table'])->getone($mid);
		$this->rs 	= $oldrs;
		$this->gongsiarr = array();
		
		$fieldarr 	= m('flow_element')->getrows("`mid`='$modeid' and `iszb`=0 $stwhe",'fields,fieldstype,name,dev,data,isbt,islu,attr,iszb,issou,gongsi,placeholder,lens','`sort`');
		$fieldarr	= $this->flow->flowfieldarr($fieldarr, $this->ismobile);
		
		$modelu		= '';
		$fieldstypearr = array();
		foreach($fieldarr as $k=>$rs){
			if($slx==1 && $oldrs){
				$rs['value'] = $oldrs[$rs['fields']];
			}
			if($lutype==1){
				$rs['isbt'] = 0;
				if($rs['issou']==1)$modelu.='{'.$rs['fields'].'}';
			}else{	
				if($rs['islu'] || $stwhe!='')$modelu.='{'.$rs['fields'].'}';
				if(!isempt($rs['gongsi']) && contain($rs['gongsi'],'{'))$this->gongsiarr[] = array(
					'iszb' 	 => 0,
					'fields' => $rs['fields'],
					'gongsi' => $rs['gongsi'],
				);
			}
			$this->fieldarr[$rs['fields']] = $rs;
			if($rs['islu']==1)$fieldstypearr[] = $rs['fieldstype'];
		}
		
		$this->smartydata['fieldsjson']	= json_encode($fieldarr);
		$this->moders	= $moders;
		$zbshu			= 0;
		$tableas		= false;
		if(!isempt($moders['tables'])){
			$tableas = explode(',', $moders['tables']);
			$zbshu	 = count($tableas);
		}
		$path 			= ''.P.'/flow/page/input_'.$num.'.html';
		if(COMPANYNUM){
			$path1 		= ''.P.'/flow/page/input_'.$num.'_'.COMPANYNUM.'.html';
			if(file_exists($path1))$path = $path1;
		}
		$pclucont 		= '';
		if(file_exists($path))$pclucont 	= file_get_contents($path);
		
		$isupfile		= 0;
		$nameaas 		= explode(',', $moders['names']); //子表名
		
		//PC端
		if($this->ismobile==0){
			$content = $pclucont;
		}else{
			$content = $modelu;
			if($tableas && $slx==0){
				foreach($tableas as $k1=>$tableass){
					$zbstr 	 = m('input')->getsubtable($modeid,$k1+1,1,1, $zbzdshu);
					if($zbzdshu>2 && $this->flow->minwidth<300)$this->flow->minwidth = $zbzdshu*180;
					if($zbstr!=''){
						$zbnam   = arrvalue($nameaas, $k1);
						$zbstr   = str_replace('{subzbname'.$k1.'}', $zbnam, $zbstr);
						$content.= '<tr><td colspan="2">';
						//if(!getconfig('inputm_view'))$content.='<div><b>'.$zbnam.'</b></div>';
						if($this->flow->minwidth>300 && $this->rock->ismobile() && 1==2){
							$content.='<div tmp="mobilezbiao" style="width:280px;overflow:auto;"><div 
						style="min-width:'.$this->flow->minwidth.'px">'.$zbstr.'</div></div>';
						}else{
							$content.='<div>'.$zbstr.'</div>';
						}
						$content.= '</td></tr>';
					}
				}
			}
			$isupfile = contain($pclucont, '{file_content}') ? 1 : 0;
			
		}
		
		if($content=='')exit('未设置录入页面,请到[流程模块→表单元素管理]下设置');
		
		$content		= $this->flow->flowinputtpl($content, $this->ismobile);
		
		$this->actclss	= $this;
		$pathss 		= ''.P.'/flow/input/mode_'.$num.'Action.php';
		if(file_exists($pathss)){
			include_once($pathss);
			$clsnam 				= 'mode_'.$num.'ClassAction';
			$this->actclss 			= new $clsnam();
			$this->actclss->flow 	= $this->flow;
			$this->actclss->mid 	= $this->mid;
			$this->actclss->rs 		= $this->rs;
			$this->actclss->ismobile= $this->ismobile;
		}
		
		//初始表单插件元素
		$this->fieldarrall	= $this->fieldarr;
		$this->inputobj	= c('input');
		$this->inputobj->ismobile 	= $this->ismobile;
		$this->inputobj->fieldarr 	= $this->fieldarr;
		$this->inputobj->flow 		= $this->flow;
		$this->inputobj->mid 		= $this->mid;
		$this->inputobj->initUser($this->adminid);
		
		$chufarr= array();
		if(method_exists($this->flow, 'flowxiangfields'))$chufarr = $this->flow->flowxiangfields($chufarr);
		$this->fieldarrall['base_sericnum'] = array('name'=>arrvalue($chufarr,'base_sericnum','单号'));
		$this->fieldarrall['base_name'] 	= array('name'=>arrvalue($chufarr,'base_name','申请人'));
		$this->fieldarrall['base_deptname'] = array('name'=>arrvalue($chufarr,'base_deptname','申请人部门'));
		$this->fieldarrall['file_content']  = array('name'=>arrvalue($chufarr,'file_content','相关文件'));
		
		preg_match_all('/\{(.*?)\}/', $content, $list);
		foreach($list[1] as $k=>$nrs){
			$str		= $this->inputobj->getfieldcont($nrs, $this->actclss);
			$content	= str_replace('{'.$nrs.'}', $str, $content);
		}
		$this->subfielsa = array();
		$content 	 	= $this->pisubduolie($content, $modeid, $nameaas);//多列子表匹配的是[]
		$content		= str_replace('*','<font color=red>*</font>', $content);
		
		//替换字段名^^
		preg_match_all('/\^(.*?)\^/', $content, $list);
		foreach($list[1] as $k=>$nrs){
			$fzdrs = arrvalue($this->fieldarrall, $nrs);
			if($fzdrs)$content	= str_replace('^'.$nrs.'^', $fzdrs['name'], $content);
		}
		
		$course			= array();
		$nowcourseid	= 0;
		if($isflow>0 && $lutype==0){
			$course[]= array('name'=>'提交','id'=>0);
			
			
			
				$courses	= $this->flow->getflowpipei($this->adminid);
				if($mid>0){
					$nowcourseid = $this->flow->billmodel->getmou('nowcourseid',"`table`='".$this->flow->mtable."' and `mid`='$mid'");
				}
				foreach($courses as $k=>$rs1){
					$na = $rs1['name'];
					if(!$this->isempt($rs1['explain']))$na.= '<br><span style="font-size:12px">('.$rs1['explain'].')</span>';
					$rs1['name'] = $na;
					$rs1['k'] 	 = $k;
					$rs1['isnow']= $rs1['id']==$nowcourseid;
					if(arrvalue($moders,'isflowlx')=='1'){
						$rs1['isnow'] = $k==0; //如果走重头审批第一步就是第一步的
					}
					
					//读取上次选择的2019-03-06 23:10:00添加
					$cuid 	= $name = '';
					if($rs1['isnow'] && $rs1['checktype']=='change' && $mid>0){
						$cheorws= $this->flow->checksmodel->getall("`table`='".$this->flow->mtable."' and `mid`='$mid' and `courseid`=".$rs1['id']."",'checkid,checkname');
						if($cheorws){
							foreach($cheorws as $k3=>$rs3){
								$cuid.=','.$rs3['checkid'].'';
								$name.=','.$rs3['checkname'].'';
							}
							if($cuid != ''){
								$cuid = substr($cuid, 1);
								$name = substr($name, 1);
							}
						}
					}
					$rs1['sysnextoptid']= $cuid;
					$rs1['sysnextopt']	= $name;
					
					$course[]=$rs1;
				}
				
			
			
			$course[]= array('name'=>'结束','id'=>-1);
		}
		$this->title  					= $this->flow->inputtitle();//录入页面的标题
		$this->smartydata['content']	= $content;
		$this->smartydata['gongsiarr']	= $this->gongsiarr;
		$this->smartydata['subfielsa']	= $this->subfielsa;
		$this->smartydata['mid']		= $mid;
		$this->smartydata['isflow']		= $isflow;
		$this->smartydata['showtype']	= $this->get('showtype');
		
		$this->smartydata['zbnamearr']	= $nameaas;
		$this->smartydata['zbshu']		= $zbshu;//子表数
		$this->smartydata['isupfile']	= $isupfile;//是否有上传
		$this->assign('inputobj', c('input'));
		
		
		
		$this->smartydata['course']		= $course;
		$inpwhere	= $this->flow->inputwidth;
		if($inpwhere<200)$inpwhere = $this->option->getval('inputwidth', 750);
		$this->smartydata['inputwidth']	= $inpwhere;
		$this->assign('fieldstypearr', $fieldstypearr);
		$otherfile = 'webmain/flow/input/tpl_input_luother_'.$this->ismobile.'.html';
		if(!file_exists($otherfile))$otherfile = '';
		$this->assign('otherfile', $otherfile);
	}
	
	//多行子表内替换
	private function pisubduolie($content, $modeid, $nameaas)
	{
		$fieldarr 	= m('flow_element')->getrows("`mid`='$modeid' and `iszb`>0",'fields,fieldstype,name,dev,data,isbt,islu,attr,iszb,gongsi,lens','`sort`');
		if(!$fieldarr)return $content;
		$this->fieldarr = array();
		foreach($fieldarr as $k=>$rs){
			$oi = $rs['iszb']-1;
			$this->fieldarr['xuhao'.$oi.''] = array(
				'fields' 	=> 'xuhao'.$oi.'',
				'fieldstype'=> 'xuhao',
				'data' 		=> '',
				'attr' 		=> ' readonly temp="xuhao"',
				'dev'	 	=> '1',
				'isbt'		=> '0',
				'fieldss'	=> 'sid'.$oi.''
			);
			$this->fieldarr[$rs['fields'].''.$oi.''] = $rs;
			if(!isempt($rs['gongsi']))$this->gongsiarr[] = array(
				'iszb' 	 => $rs['iszb'],
				'fields' => $rs['fields'],
				'gongsi' => $rs['gongsi'],
			);
			if($rs['isbt']=='1'){
				$this->subfielsa[] = array(
					'name' 	 => $rs['name'],
					'fields' => $rs['fields'],
					'type'   => $rs['fieldstype'],
					'isbt' => $rs['isbt'],
					'iszb' => (int)$rs['iszb'],
					'zbname'=> arrvalue($nameaas, $oi)
				);
			}
		}
		$this->inputobj->fieldarr 	= $this->fieldarr;
		preg_match_all('/\[(.*?)\]/', $content, $list);
		foreach($list[1] as $k=>$nrs){
			if(!$this->isempt($nrs)){
				$fida= explode(',', $nrs);$xu0='0';
				if(isset($fida[1]))$xu0=$fida[1];
				$iszb		= floatval(substr($fida[0],-1))+1;//第几个子表如果超过第10个子表就麻烦了
				$str		= $this->inputobj->getfieldcont($fida[0], $this->actclss,'_'.$xu0.'', $iszb);
				$content	= str_replace('['.$nrs.']', $str, $content);
			}
		}
		return $content;
	}
	
	public function getselectdataAjax()
	{
		$rows 	= array();
		$act	= $this->get('act');
		$modenum= $this->get('sysmodenum');
		$actstr = $this->get('actstr');
		if(isempt($act)){
			if($actstr){
				$actstr1 = $this->jm->base64decode($actstr);
				$rows 	 = c('input')->sqlstore($actstr1);
			}
			return $rows;
		}
		//用:读取model上的数据
		if(!isempt($act) && contain($act,':')){
			$acta = explode(':', $act);
			$objs = m($acta[0]);
			$tacs = $acta[1];
			if(method_exists($objs, $tacs)){
				$rows = $objs->$tacs();
			}
		}
		
		if(!$rows && !isempt($act) && method_exists($this, $act)){
			$rows = $this->$act();
		}
		//从Model上读取
		if(!$rows && !isempt($modenum)){
			$this->flow = m('flow')->initflow($modenum);
			if(method_exists($this->flow, $act)){
				$rows = $this->flow->$act();
			}
		}
		//从数据选项读取
		if(!$rows && $actstr){
			$acta = explode(',', $this->jm->base64decode($actstr));
			if(count($acta)<=3){
				$sarr = m('option')->getmnum($acta[0]);
				if($sarr){
					$vas = arrvalue($acta,2, 'value');
					foreach($sarr as $k=>$rs){
						$rows[] = array(
							'name'  => $rs['name'],
							'value' => $rs[$vas],
						);
					}
				}
			}
		}
		
		
		return $rows;
	}
	
	
	
	
	
	/**
	*	公共读取数据之前处理
	*/
	public function storebeforeshow($table)
	{
		$this->atypearr	= false;
		$this->modeid 	= (int)arrvalue($this->flow->moders, 'id', $this->get('modeid','0'));
		$pnum			= $this->get('pnum');
		if($this->post('atype')=='grant'){
			$this->atypearr = array();
			$this->atypearr[] = array(
				'id'	=> 0,
				'num'	=> 'grant',
				'name'  => ''.$this->flow->modename.'授权查看',
			);
		}else if($this->loadci==1 && $this->adminid>0){
			$this->atypearr = m('where')->getmywhere($this->modeid, $this->adminid, $pnum);
			if(isempt($pnum)){
				$mors = $this->flow->moders;
				if((int)arrvalue($mors,'iscs','0')>0)$this->atypearr[] = array(
					'id'	=> 0,
					'num'	=> 'chaos',
					'name'  => ''.$mors['name'].'抄送给我',
				);
				if($mors['isflow']>0){
					$this->atypearr[] = array(
						'id'	=> 0,
						'num'	=> 'mychuli',
						'name'  => ''.$mors['name'].'经我处理',
					);
				}
			}
		}
		return $this->storebefore($table);
	}
	
	/**
	*	公共读取数据之后处理，展示列数
	*/
	public function storeaftershow($table, $rows)
	{
		$barr['rows'] 		= $rows;
		$barr['atypearr'] 	= $this->atypearr;
		if($this->loadci==1){
			$vobj	= m('view');
			$barr['isadd'] 		= $vobj->isadd($this->modeid, $this->adminid); //判断是否可添加
			$barr['isdaoru'] 	= $vobj->isdaoru($this->modeid, $this->adminid); //判断是否可导入
			$barr['isdaochu'] 	= $vobj->isdaochu($this->modeid, $this->adminid); //判断是否可导入
			$barr['listinfo']	= m('mode')->createlistpage($this->flow->moders,0,1,$this);
		}
		$barr['souarr']		= $this->flow->flowsearchfields();
		$rows 				= $this->flow->viewjinfields($rows);//禁看字段处理
		$farrl	= array();
		foreach($this->flow->fieldsarra as $k2=>$rs2){
			if($rs2['fieldstype']=='uploadimg')$farrl[$rs2['fields']]=$rs2['fieldstype'];
		}
		
		if($rows)foreach($rows as $k1=>$rs1){
			foreach($farrl as $fid=>$flx){
				if(isset($rs1[$fid])){
					$val = $rs1[$fid];
					if($flx=='uploadimg'){
						$val = $this->rock->gethttppath($val);
						$rows[$k1][$fid] = $val;
						//if($this->flow->modeid>92)$val='<img src="'.$val.'" height="60">';
					}
				}
			}
		}
		$barr['modeid'] 	= $this->modeid;
		$barr['loadci'] 	= $this->loadci;
		$barr['rows'] 		= $rows;
		$scarr 				= $this->storeafter($table, $rows);
		if(is_array($scarr))foreach($scarr as $k=>$v)$barr[$k]=$v;
		return $barr;
	}
	
	//获取可搜索列表
	public function getcolumnsAjax()
	{
		$modeid 	= (int)$this->get('modeid');
		$modenum 	= $this->get('modenum');
		$flow 		= m('flow')->initflow($modenum);

		$souarr 	= array();
		$this->input= c('input');
		foreach($flow->fieldsarra as $k=>$rs){
			
			if($rs['issou']==1){
				$rs['store'] = $this->input->getdatastore($rs['fieldstype'], $this, $rs['data']);
				$souarr[] = $rs;
			}
		}
		$this->returnjson($souarr);
	}
	
	//初始化导入
	public function initdaoruAjax()
	{
		$modenum 	= $this->get('modenum');
		$flow 		= m('flow')->initflow($modenum);
		$rows 		= m('flow_element')->getall('mid='.$flow->modeid.' and `isdr`=1 and `iszb`=0','name,isbt,fields','`sort`,`id`');
		return $rows;
	}
	//确定导入数据
	public function daorudataAjax()
	{
		$modenum 	= $this->post('modenum');
		$flow 		= m('flow')->initflow($modenum);
		$rows 		= m('flow_element')->getall('mid='.$flow->modeid.' and `isdr`=1 and `iszb`=0','name,isbt,fields,isonly','`sort`,`id`');
		$fields 	= $fieldss = '';
		if(!$rows)return returnerror('没有导入的字段');
		$onlyfield	= array();
		foreach($rows as $k=>$rs){
			$fields.=','.$rs['fields'].'';
			if($rs['isbt']=='1')$fieldss.=','.$rs['fields'].'';
			if($rs['isonly']=='1')$onlyfield[] = $rs['fields']; //唯一字段
		}
		$fields = substr($fields, 1);
		if($fieldss!='')$fieldss = substr($fieldss,1);
		
		$data  	= c('html')->importdata($fields, $fieldss); //获取提交过来要导入的数据库
		if(!$data)return returnerror('没有可导入的数据,注意*是必填的哦');
		
		$msgstr = '';
		
		//保存前判断
		if(method_exists($flow,'flowdaorubefore')){
			$data = $flow->flowdaorubefore($data);
			if(is_string($data))return returnerror($data);
		}
		
		//判断是否有重复
		$ldata 	= array();
		foreach($data as $k=>$rs){
			$bos 	= true;
			foreach($onlyfield as $onid){
				$val = arrvalue($rs, $onid);
				if(!isempt($val)){
					$tos = $flow->rows("`$onid`='$val'");
					if($tos>0){
						$bos = false;
						$msgstr.='行'.($k+1).'的字段'.$onid.'存在重复;';
						break;
					}
				}
			}
			if($bos)$ldata[] = $rs;
		}
		if(!$ldata)return returnerror('没有可导入的数据'.$msgstr.'');
		$allfields = $this->db->getallfields('[Q]'.$flow->mtable.'');
		
		$oi 	= 0;
		$dorudat= array();
		foreach($ldata as $k=>$rs){
		
			$id 	= (int)arrvalue($rs,'id','0');
			$where 	= '';
			if($id>0){
				if($flow->rows($id)>0){
					$where='`id`='.$id.'';
				}else{
					$id = 0;
				}
			}

			if($id==0){
				if(!isset($rs['createid']) && in_array('createid', $allfields))$rs['createid'] = $this->adminid;
				if(!isset($rs['createname']) && in_array('createname', $allfields))$rs['createname'] = $this->adminname;
				if(!isset($rs['adddt']) && in_array('adddt', $allfields))$rs['adddt'] = $this->now;
				if(!isset($rs['createdt']) && in_array('createdt', $allfields))$rs['createdt'] = $this->now;
				if(!isset($rs['comid']) && in_array('comid', $allfields))$rs['comid'] = m('admin')->getcompanyid();
			}
			
			if(!isset($rs['uid']) && in_array('uid', $allfields))$rs['uid'] = $this->adminid;
			if(!isset($rs['optid']) && in_array('optid', $allfields))$rs['optid'] = $this->adminid;
			if(!isset($rs['optname']) && in_array('optname', $allfields))$rs['optname'] = $this->adminname;
			if(!isset($rs['optdt']) && in_array('optdt', $allfields))$rs['optdt'] = $this->now;
			if(!isset($rs['applydt']) && in_array('applydt', $allfields))$rs['applydt'] = $this->date;
			
			if($id==0){
				$bo = $flow->insert($rs);
			}else{
				$bo = $id;
				$flow->update($rs, $where);
			}
			if($bo){
				$rs['id'] = $bo;
				$dorudat[]= $rs;
				$oi++;
				
				//有流程的模块就要提交操作
				$status = arrvalue($rs,'status','0'); //状态
				$isturn = arrvalue($rs,'isturn','1'); //默认是提交的
				if($flow->isflow>0 && $status=='0'){
					$flow->loaddata($rs['id'], false);
					$na = ($isturn=='1') ? '提交' : '保存';
					$flow->submit($na);
				}
				
			}else{
				$msgstr.='行'.($k+1).'保存数据库错误;';
			}
		}
		
		if($oi==0)return returnerror('导入数据为0条'.$msgstr.'');
		
		//保存后判断
		if(method_exists($flow,'flowdaoruafter')){
			$flow->flowdaoruafter($dorudat);
		}

		return returnsuccess('成功导入'.$oi.'条数据'.$msgstr.'');
	}
	
	//读取导入的excel数据
	public function readxlsAjax()
	{
		$fileid = (int)$this->get('fileid','0');
		$fpath  = m('file')->getmou('filepath', $fileid);
		if(isempt($fpath))return returnerror('文件不存在了');
		$phpexcel 	= c('PHPExcelReader');
		$rows   	= $phpexcel->reader($fpath);
		if(is_string($rows))return returnerror('无法读取Excel文件('.$rows.')');
		$modenum= $this->get('modenum');
		$flow	= m('flow')->initflow($modenum);
		$dtarr	= array();//日期读取需要判断
		$xuha	= -1;
		foreach($flow->fieldsarra as $k2=>$rs2){
			if($rs2['isdr']=='1' && $rs2['iszb']=='0'){
				$xuha++;
				if(in_array($rs2['fieldstype'], array('date','datetime'))){
					$dtarr[$phpexcel->A[$xuha]] = $rs2['fieldstype'];
				}
			}
		}
		$str = '';
		foreach($rows as $k=>$rs){
			$str1 = '';
			$xi   = 0;
			foreach($rs as $k1=>$v1){
				if($xi>0)$str1.='	';
				if(isset($dtarr[$k1]) && is_numeric($v1)){
					$v1 = $phpexcel->ExcelToDate($dtarr[$k1], $v1);
				}
				$v1 	= str_replace("\n", '[XINHUBR]', $v1); //有\n转
				$str1.=''.$v1.'';
				$xi++;
			}
			if($k>0)$str.="\n";
			$str.=$str1;
		}
		
		return returnsuccess($str);
	}
	
	//下载导入的模版
	public function daoruexcelAction()
	{
		$this->display = false;
		$modenum 	= $this->get('modenum');
		$flow 		= m('flow')->initflow($modenum);
		$rows 		= m('flow_element')->getall('mid='.$flow->modeid.' and `isdr`=1 and `iszb`=0','name,isbt,fields','`sort`,`id`');
		if(!$rows)return '对应模块没有设置导入字段';
		$testdata	= $texdata = array();
		if(method_exists($flow,'flowdaorutestdata')){
			$testdata = $flow->flowdaorutestdata();
		}
		m('file')->fileheader(''.$modenum.'import.xls');
		$str1 	= '';
		$str2 	= '';
		$col 	= 0;
		$headArr	= array();
		foreach($rows as $k=>$rs){
			$col++;
			$xi 	= $rs['isbt']=='1'? '<font color=red>*</font>' : '';
			$x1 	= $rs['isbt']=='1'? '*' : '';
			$str1.='<td style="border:.5pt #000000 solid; background:#cdf79e" height="30" align="center">'.$xi.'<b>'.$rs['name'].'('.$rs['fields'].')</b></td>';
			$headArr[$rs['fields']] = ''.$x1.''.$rs['name'].'('.$rs['fields'].')';
		}
		if($testdata){
			$texdata = $testdata;
			if(!isset($testdata[0]))$texdata = array($testdata);
			foreach($texdata as $j=>$jrs){
				$str2.='<tr>';
				foreach($rows as $k=>$rs){
					$val  = arrvalue($jrs, $rs['fields']);
					$str2.='<td style="border:.5pt #000000 solid;" height="30" align="center">'.$val.'</td>';
				}
				$str2.='</tr>';
			}
		}

		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table style="border-spacing: 0;border-collapse: collapse;"><tr bgcolor="#f1f1f1">'.$str1.'</tr>'.$str2.'';
		for($i=1;$i<=100;$i++){
			$str.='<tr>';
			for($j=1;$j<=$col; $j++){
				$str.='<td style="border:.5pt #000000 solid" height="30" align="center"></td>';
			}
			$str.='</tr>';
		}
		$str.= '</table>';
		
		$pexecl = c('PHPExcel');
		if($pexecl->isBool()){
			$pexecl->title 		= ''.$modenum.'_import';
			$pexecl->titlebool 	= false;
			$pexecl->borderbool = false;
			$pexecl->headArr 	= $headArr;
			$pexecl->rows 		= $texdata;
			$pexecl->display('xls', 'down');
		}else{
			return $str;
		}
	}
	
	public function getuinfoAjax()
	{
		$uid = $this->post('uid', $this->adminid);
		$rs	 = m('admin')->getone($uid,'id,name,deptname,deptid,deptallname,ranking,workdate,pingyin');
		$unrs= m('userinfo')->getone($uid, 'syenddt,positivedt');
		if($unrs)foreach($unrs as $k=>$v)$rs[$k] =$v;
		return $rs;
	}
	
	public function upimagepathAjax()
	{
		$fileid = (int)$this->get('fileid');
		$fid  	= $this->get('fid');
		$frs  	= m('file')->getone($fileid);
		$path   = '';
		if(!isempt($frs['thumbplat'])){
			$path = str_replace('_s.','.',$frs['thumbplat']);
		}
		$filepathout = arrvalue($frs,'filepathout');
		if($filepathout)$path = $filepathout;
		return returnsuccess(array(
			'path' => $path,
			'fid'  => $fid,
		));
	}
	
	public function saveoptionAction()
	{
		$num  = $this->post('num');
		$name = $this->post('name');
		if($name && $num){
			$pid = $this->option->getpids($num);
			if($pid>0){
				$this->option->insert(array(
					'pid' => $pid,
					'name' => $name,
					'optdt' => $this->now,
					'optid' => $this->adminid,
				));
			}
		}
		return 'ok';
	}
}

class inputClassAction extends inputAction{}