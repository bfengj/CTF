<?php 
/**
*	系统表单插件
*/
class inputChajian extends Chajian
{
	public $fieldarr 	= array();
	public $flow 		= null;
	public $ismobile 	= 0;
	public $urs 		= array();
	public $mid 		= 0;
	
	protected function initChajian()
	{
		$this->date = $this->rock->date;
		$this->now 	= $this->rock->now;
		$this->option 	= m('option');
	}
	
	public function initUser($uid)
	{
		$this->adminid 	= $uid;
		$this->urs  	= m('admin')->getone($uid, '`name`,`deptname`');
		$this->adminname= $this->urs['name'];
	}
	
	public function initFields($stwhe='')
	{
		$fieldarr 	= m('flow_element')->getrows($stwhe,'*','`sort`');
		foreach($fieldarr as $k=>$rs){
			$this->fieldarr[$rs['fields']] = $rs;
		}
		return $fieldarr;
	}
	
	/**
	*	读取表单样式(有默认值的)
	*/
	public function getfieldcontval($fid, $val=false, $objs=null)
	{
		return $this->getfieldcont($fid, $objs,'',0, $val);
	}
	
	/**
	*	读取表单样式
	*/
	public function getfieldcont($fid, $objs=null, $leox='', $iszb=0, $deval=false)
	{
		$fida= explode(',', $fid);$xu0='0';
		$ism = $this->ismobile;
		$fid = $fida[0];
		$str = $val ='';
		if(isset($fida[1]))$xu0=$fida[1];
		if($fid=='base_name'){
			$str = '<input class="inputs" style="border:none;background:none" name="base_name" value="'.$this->adminname.'" readonly>';
		}
		if($fid=='base_deptname'){
			$str = '<input class="inputs" style="border:none;background:none" name="base_deptname" value="'.$this->urs['deptname'].'" readonly>';
		}
		if($fid=='base_sericnum'){
			$str = '<input class="inputs" style="border:none;background:none" name="base_sericnum" value="'.$this->flow->createnum().'" readonly>';
		}
		if($fid=='file_content'){
			$str = '<input name="fileid" type="hidden" id="fileidview-inputEl"><div id="view_fileidview" style="width:98%;height:auto;min-height:60px;border:1px #cccccc solid; background:white;overflow:auto"></div><div id="fileupaddbtn"><a href="javascript:;" class="blue" onclick="c.upload()"><u>＋添加文件</u></a></div>';
		}

		if($fid=='删'){
			$str='<a href="javascript:;" onclick="c.delrow(this,'.$xu0.')">删</a>';
		}
		if($fid=='新增'){
			$str='<a href="javascript:;" onclick="c.addrow(this,'.$xu0.')">＋新增</a>';
		}
		if($str!='')return $str;
		if(!isset($this->fieldarr[$fid]))return '';
		
		$isasm 	= 1;
		$a 		= $this->fieldarr[$fid];
		$fname 	= $fid.$leox;
		$type 	= $a['fieldstype'];
		$placeholder 	= arrvalue($a, 'placeholder');
		$isbt 	= arrvalue($a, 'isbt');
		$data 	= $a['data'];
		$val 	= $a['dev'];
		if(isset($a['value']))$val=$a['value'];
		$attr 	= $a['attr'];
		$lens 	= (int)arrvalue($a, 'lens','0');
		$styles = '';
		$style	= '';
		if(contain($attr,',')){
			$attra = explode(',', $a['attr']);
			$style = $attra[1];
			$attr  = $attra[0];
		}
		if(!isempt($style))$styles=' style="'.$style.'"';
		$fnams 	= $this->rock->arrvalue($a,'name');$fieldname = $fnams;
		if($isbt==1)$fnams='*'.$fnams.'';
		$val	= $this->rock->get('def_'.$fname.'', $val);
		if(isempt($val))$val='';
		if($deval !== false)$val = $deval; //设置默认值
		if(isempt($attr))$attr='';
		if($val!='' && contain($val,'{')){
			$val 	= m('base')->strreplace($val, $this->adminid, 1);
			if($val=='{sericnum}' && $this->flow!=null)$val = $this->flow->createnum();
		}
		if($type=='num'){
			if($this->flow != null){
				$val = $this->flow->createinputnum($data, $fid);
			}
		}
		
		//读默认值
		if($objs != null && method_exists($objs, 'inputfieldsval')){
			$_vals = $objs->inputfieldsval($fname, $a);
			if(!isempt($_vals))$val = $_vals;
		}
		
		if(!isempt($placeholder))$attr.=' placeholder="'.$placeholder.'"';
		
		if($type=='email' || $type=='tel' || $type=='mobile' || $type=='url'){
			$attr.=' inputtype="'.$type.'"';
		}
		$lenstr = '';
		if($lens>0)$lenstr=' maxlength="'.$lens.'"';
		$onblue = ' onblur="c.inputblur(this, '.$iszb.')"';
		$iszhang= false;
		$str 	= '<input class="inputs" type="text" value="'.$val.'" '.$attr.''.$onblue.''.$styles.''.$lenstr.' name="'.$fname.'">';
		
		
		if($type=='fixed'||$type=='hidden'){
			$str  = '<input value="'.$val.'" '.$attr.' type="hidden" name="'.$fname.'">';
			$isasm=0;
		}
		if($type=='textarea'){
			$iszhang= false;
			$str = '<textarea class="textarea" onblur="js.changdu(this)" style="height:80px;'.$style.'" '.$attr.''.$lenstr.' name="'.$fname.'">'.$val.'</textarea>';
		}
		if($type=='rockcombo' || $type=='select' || $type=='checkboxall' || $type=='radio'){
			$attr.=' onchange="c.inputblur(this, '.$iszb.')"';
			$str ='<select style="width:99%;'.$style.'" '.$attr.' name="'.$fname.'" class="inputs">';
			$str.='<option value="">-请选择-</option>';
			$str1= '';
			$str2= '';
			
			$datanum 	= $data;
			$fopt		= $this->getdatastore($type, $objs, $datanum, $fid);
			$optgroup	= '';
			if($fopt)foreach($fopt as $k=>$rs){
				$_val= arrvalue($rs,'value', $rs['name']);
				$sel = ($_val==$val)?'selected':'';
				$sel2 = ($_val==$val)?'checked':'';
				$ocn = '';
				if($type=='select')foreach($rs as $k1=>$v1)if($k1!='id'&&$k1!='value'&&$k1!='name')$ocn.=' '.$k1.'="'.$v1.'"';
				if($type=='select' && isset($rs['optgroup']) && !isempt($rs['optgroup'])){
					if($optgroup!=$rs['optgroup']){
						if($optgroup!='')$str.='</optgroup>';
						$str.='<optgroup label="'.$rs['optgroup'].'">';
					}
					$optgroup = $rs['optgroup'];
				}
				$str.='<option'.$ocn.' value="'.$_val.'" '.$sel.'>'.$rs['name'].'</option>';
				
				$str1.='<label><input name="'.$fname.'[]" value="'.$_val.'" type="checkbox">'.$rs['name'].'</label>&nbsp;&nbsp;';
				$str2.='<label><input'.$ocn.' name="'.$fname.'" '.$sel2.' value="'.$_val.'" type="radio">'.$rs['name'].'</label>&nbsp;&nbsp;';
			}
			if($type=='select' && $optgroup!='')$str.='</optgroup>';
			$str.='</select>';
			if($type=='checkboxall')$str = $str1;
			if($type=='radio')$str = $str2;
		}
		
		if($type=='datetime'||$type=='date'||$type=='time'||$type=='month'){
			$str = '<input onclick="js.datechange(this,\''.$type.'\')" value="'.$val.'" '.$attr.''.$onblue.''.$styles.' class="inputs datesss" inputtype="'.$type.'" readonly name="'.$fname.'">';
		}
		//数字类型
		if($type=='number'){
			$str 	= '<input class="inputs" '.$attr.''.$styles.' value="'.$val.'" type="number" onfocus="js.focusval=this.value" '.$lenstr.' onblur="js.number(this);c.inputblur(this,'.$iszb.')" name="'.$fname.'">';
		}
		if($type=='xuhao'){
			$str = '<input class="inputs xuhao" '.$attr.' type="text" value="'.$val.'" name="'.$fname.'">';
			$str.= '<input value="0" type="hidden" name="'.$a['fieldss'].$leox.'">';
		}
		if($type=='changeusercheck'||$type=='changeuser'||$type=='changedept'||$type=='changedeptcheck'||$type=='changedeptusercheck'){
			$zbnae	= $data;
			if($iszb>0)$zbnae = $data.''.($iszb-1).''.$leox.'';
			$str 	= $this->inputchangeuser(array(
				'name' 	=> $fname,
				'id' 	=> $zbnae,
				'value'	=> $val,
				'type' 	=> $type,
				'title' => $fieldname,
				'changerange' => arrvalue($a, 'gongsi'),
				'placeholder' => $placeholder,
				'attr'  => $onblue,
				'style' => '',
			));
		}
		if($type=='selectdatafalse' || $type=='selectdatatrue'){
			$str 	= '<table width="98%" cellpadding="0" border="0"><tr><td width="100%"><input '.$attr.''.$onblue.''.$styles.' class="inputs" style="width:99%" value="'.$val.'" readonly type="text" name="'.$fname.'"></td>';
			$str   .= '<td nowrap>';
			if($isbt=='0')$str   .= '<button onclick="c.selectdataclear(\''.$fname.'\',\''.$data.'\','.$iszb.')" class="webbtn" type="button">x</button>';
			$str   .= '<button type="button" onclick="c.selectdata(\''.$data.'\','.substr($type,10).',\''.$fname.'\',\''.$fieldname.'\','.$iszb.')" class="webbtn">选</button></td></tr></table>';
		}
		if($type=='ditumap'){
			$zbnae	= $data;
			if($iszb>0)$zbnae = ''.($iszb-1).''.$leox.'';
			$str 	= '<table width="99%" cellpadding="0" border="0"><tr><td width="100%"><input '.$attr.''.$onblue.''.$styles.''.$lenstr.' class="inputs" style="width:99%" value="'.$val.'" type="text" name="'.$fname.'"></td>';
			$str   .= '<td nowrap>';
			if($isbt=='0')$str   .= '<button onclick="c.selectmapclear(\''.$fname.'\',\''.$zbnae.'\','.$iszb.')" class="webbtn" type="button">x</button>';
			$str   .= '<button type="button" onclick="c.selectmap(\''.$fname.'\',\''.$zbnae.'\',\''.$fieldname.'\','.$iszb.')" class="webbtn">选</button></td></tr></table>';
		}
		if($type=='htmlediter'){
			$iszhang= false;
			$str = '<textarea class="textarea" style="height:130px;'.$style.'" '.$attr.' name="'.$fname.'">'.$val.'</textarea>';
		}
		if($type=='checkbox'){
			$chk = '';
			if($val=='1'||$val=='true')$chk='checked';
			$str = '<input name="'.$fname.'" '.$chk.' '.$attr.''.$styles.' type="checkbox" value="1"> ';
		}
		if($type=='uploadimg'){
			$str = '<input name="'.$fname.'" value="'.$val.'" type="hidden">';
			$str.= '<img src="images/noimg.jpg" onclick="c.showviews(this)" id="imgview_'.$fname.'" height="100">';
			$str.= '<div style="display:" tsye="img" tnam="'.$fname.'" id="filed_'.$fname.'"><a href="javascript:;" onclick="c.uploadimgclear(\''.$fname.'\')">删</a>&nbsp;<input onclick="c.initupss(\''.$fname.'\');" type="file" style="width:120px" accept="image/jpg,image/jpeg,image/png" id="filed_'.$fname.'_inp"></div>';
		}
		if($type=='uploadfile'){
			$str = '<input name="'.$fname.'" value="'.$val.'" type="hidden">';
			$str.= '<div style="display:inline-block" id="fileview_'.$fname.'"><div onclick="c.uploadfilei(\''.$fname.'\')" style="display:;border:dashed 1px #cccccc" id="'.$fname.'_divadd" class="upload_items"><img class="imgs" src="images/jia.png"></div></div>';
			$str.= '<div style="display:none" tsye="file" tnam="'.$fname.'" tdata="'.$data.'" id="filed_'.$fname.'"><input type="file" style="width:120px" id="filed_'.$fname.'_inp"></div>';
		}
		if($type=='auto'){
			$datanum = $data;
			if(!isempt($datanum)){
				if($objs!=null && method_exists($objs, $datanum)){
					$str = $objs->$datanum($this->mid, $this->flow);
				}
			}
		}
		if($iszb>0)return $str;
		if($isasm==1){
			$lx  = 'span';if($ism==1)$lx='div';
			$str = '<'.$lx.' id="div_'.$fname.'" class="divinput">'.$str.'</'.$lx.'>';
			if($ism==1 && $iszb==0){
				if($iszhang){
					$str = '<tr class="lumtr"><td colspan="2"><div style="padding-left:10px;padding-top:10px">'.$fnams.'</div>'.$str.'</td></tr>';
				}else{
					$str = '<tr class="lumtr"><td class="lurim" nowrap>'.str_replace(' ','<br>', $fnams).'</td><td width="90%">'.$str.'</td></tr>';
				}
			}
		}
		return $str;
	}
	
	/**
	*	输出选择人员html
	*/
	public function inputchangeuser($arr=array())
	{
		$oarr   = array(
			'name'=>'',
			'id'=>'',
			'type'=>'changeuser',
			'value'=> '',
			'valueid'=> '',
			'title' => '',
			'changerange' => '',
			'placeholder' => '',
			'attr' => '',
		);
		foreach($arr as $k=>$v)$oarr[$k]=$v;
		$fname  = $oarr['name'];
		$zbnae  = $oarr['id'];
		$type   = $oarr['type'];
		$valea  = explode('|', $oarr['value']);
		$_vals0	= $valea[0];
		$_vals1	= arrvalue($valea,1, $oarr['valueid']);
		
		$str 	= '<table width="99%" cellpadding="0" border="0"><tr><td width="100%"><input class="inputs" style="width:99%" '.$oarr['attr'].' placeholder="'.$oarr['placeholder'].'" id="change'.$fname.'" value="'.$_vals0.'" readonly type="text" name="'.$fname.'"><input name="'.$zbnae.'" value="'.$_vals1.'" id="change'.$fname.'_id" type="hidden"></td>';
		$str   .= '<td nowrap><button onclick="js.changeclear(\'change'.$fname.'\')" class="webbtn" type="button">x</button><button id="btnchange_'.$fname.'" onclick="js.changeuser(\'change'.$fname.'\',\''.$type.'\',\''.$oarr['title'].'\' ,{changerange:\''.$oarr['changerange'].'\'})" type="button" class="webbtn">选</button></td></tr></table>';
		
		return $str;
	}
	
	private function issql($str)
	{
		$bo 	= false;
		$str 	= strtoupper($str);
		if(contain($str,' FROM '))$bo=true;
		return $bo;
	}
	
	public function getdatastore($type, $objs, $datanum, $fid='')
	{
		$fopt	= array();
		$tyepa 	= explode(',','rockcombo,select,checkboxall,radio');
		if(!in_array($type, $tyepa) || isempt($datanum))return $fopt;
		
		//判断是不是SQL([SQL] name,value from [Q]abc)
		if($this->issql($datanum)){
			$sql  = str_replace('[SQL]','select ', $datanum);
			$sql  = m('base')->strreplace($sql);
			$rows = $this->db->getall($sql);
			if($rows)foreach($rows as $k=>$rs){
				$nam = arrvalue($rs,'name');
				$val = $nam;
				if(isset($rs['id']))$val 	= $rs['id'];
				if(isset($rs['value']))$val = $rs['value'];
				$fopt[] = array(
					'name' => $nam,
					'value' => $val,
				);
			}
		}
		
		//2021-02-26新增新的数据源,开头
		if(substr($datanum,0,1)==','){
			return $this->sqlstore($datanum);
		}
		
		
		//用:读取model上的数据
		if(!$fopt && !isempt($datanum) && contain($datanum,':')){
			$tata = explode(',', $datanum);
			$acta = explode(':', $tata[0]);
			$objs = m($acta[0]);
			$tacs = $acta[1];
			$cshu1= arrvalue($tata, 1);
			if(method_exists($objs, $tacs)){
				$fopt = $objs->$tacs($cshu1);
				if(is_array($fopt)){
					return $fopt;
				}
			}
		}
		
		
		//自定义方法读取数据源
		if(!$fopt && $objs!=null && method_exists($objs, $datanum)){
			$fopt = $objs->$datanum($fid,$this->mid);
			if(is_array($fopt)){
				return $fopt;
			}
		}
		
		//从flow上读取
		if(!$fopt && $this->flow!=null && method_exists($this->flow, $datanum)){
			$fopt = $this->flow->$datanum($fid,$this->mid);
			if(is_array($fopt)){
				return $fopt;
			}
		}
		
		if(!$fopt && ($type=='rockcombo' || $type=='checkboxall' || $type=='radio')){
			$_ars = explode(',', $datanum);
			$fopt = $this->option->getselectdata($_ars[0], isset($_ars[2]));
			$fvad = 'name';
			if(isset($_ars[1])&&($_ars[1]=='value'||$_ars[1]=='id'||$_ars[1]=='num'))$fvad=$_ars[1];
			
			if($fopt){
				foreach($fopt as $k=>$rs){
					$fopt[$k]['value'] = $rs[$fvad];
				}
				if($type=='rockcombo' && $fvad=='name' && M=='input'){
					//$fopt[] = array('name'  => '其它..','value' => $_ars[0],);
				}
			}
		}
		if(!$fopt && ($type=='select' || $type=='checkboxall' || $type=='radio')){
			$fopt = c('array')->strtoarray($datanum);
			$barr = array();
			foreach($fopt as $k=>$rs){
				$barr[] = array(
					'name'	=> $rs[1],
					'value' => $rs[0],
				);
			}
			$fopt = $barr;
		}
		return $fopt;
	}
	
	/**
	*	新的获取数据源方法
	*/
	public function sqlstore($actstr1)
	{
		$rows = array();
		$acta = explode(',', $actstr1);
		if(count($acta)>=3){
			if($acta[1]){
				$cats = explode(','.$acta[1].',', $actstr1);
				$sqlw = $cats[1];
			}else{
				$sqlw = substr($actstr1,2);
			}
			$sqla = explode('|', $sqlw);
			$wher = arrvalue($sqla,2,'1=1');
			if(contain($wher,'{'))$wher = m('where')->getstrwhere($wher,$this->adminid);
			$wher = str_replace('$','"', $wher);
			$rowa = m($sqla[0])->getall($wher,$sqla[1]);
			$ndf  = 'name';
			$vdf  = 'id';
			if($rowa)foreach($rowa as $k=>$rs1){
				if($k==0){
					if(!isset($rs1[$ndf])){
						foreach($rs1 as $k1=>$v1){$ndf = $k1;break;}
					}
					if(!isset($rs1[$vdf])){
						$xus = 0;
						foreach($rs1 as $k1=>$v1){
							$xus++;
							$vdf = $k1;
							if($xus>=2)break;
						}
					}
				}
				$rs1['name']  = $rs1[$ndf];
				$rs1['value'] = $rs1[$vdf];
				$rows[] = $rs1;
			}
		}
		return $rows;
	}
}                                              