<?php
/**
*	可视化模块设计
*	开发者：雨中磐石
*	官网：www.rockoa.com
*	禁止乱修改头部信息，请保留尊重开发者
*	修改时间：2021-10-14
*/
class flowviewClassAction extends Action
{
	public function defaultAction()
	{
		$ybarr	 = $this->option->authercheck();
		if(is_string($ybarr))return $ybarr;
		$this->title = '可视化模块设计';
		$this->assign('modearr', m('mode')->getmodearr("and (`type`<>'系统' or `num`='demo')"));
	}
	
	//加载字段信息
	public function getziduanAction()
	{
		$num 	= $this->get('num');
		$fid 	= $this->get('fid');
		$moders = m('flow_set')->getone("`num`='$num'");
		$tables = $moders['table'];
		$ziduan = m('flow_element')->getone('mid='.$moders['id'].' and `iszb`=0 and `fields`=\''.$fid.'\'','*','`iszb` asc,`sort` asc');
		
		$fiedar = $this->db->gettablefields('[Q]'.$tables.'','',"and `COLUMN_NAME`='$fid'");
		if($fiedar){
			$fieda = $fiedar[0];
			if($fieda['type']=='decimal'){
				$ziduan['lens'] 	= $fieda['xslen1'];
				$ziduan['xiaoshu'] 	= $fieda['xslen2'];
			}
		}
	
		return array(
			'ziduan' => $ziduan,
			'fiedar' => $fiedar,
			'typedata' => $this->option->getdata('flowinputtype'),
		);
	}
	
	//加载字段信息
	public function getziduansAction()
	{
		$num 		= $this->get('num');
		$name 		= $this->rock->xssrepstr($this->post('name'));
		$fields 	= c('pingyin')->get($name,1);
		if(!$fields || strlen($fields)<4)$fields = 'a'.$this->db->ranknum('flow_element','fields'); 
		if(strlen($fields)>20)$fields = 'a'.substr($fields,0,19);
		
		$ziduan['name'] 	= $name;
		$ziduan['fields'] 	= $fields;
		$ziduan['fieldstype'] = 'text';
		$ziduan['id'] 	= '0';
		$ziduan['dev'] 	= '';
		$ziduan['lens'] = '0';
		$ziduan['islu'] = '1';
		$ziduan['issou'] = '1';
		$ziduan['xiaoshu'] = '0';
		return array(
			'ziduan' => $ziduan,
			'typedata' => $this->option->getdata('flowinputtype'),
		);
	}
	//保存字段
	public function saveziduanAction()
	{
		$id 	= (int)$this->post('id','0');
		$num 	= $this->post('modenum');
		$moders = m('flow_set')->getone("`num`='$num'");
		$mid	= $moders['id'];

		$sarr['name'] 	= $this->post('name');
		$sarr['fieldstype'] 	= $this->post('fieldstype');
		$sarr['dev'] 			= $this->post('dev');
		$sarr['placeholder'] 	= $this->post('placeholder');
		$sarr['data'] 	= $this->post('data');
		$sarr['lens'] 	= (int)$this->post('lens','0');
		$sarr['isbt'] 	= (int)$this->post('isbt','0');
		$sarr['islu'] 	= (int)$this->post('islu','0');
		$sarr['issou'] 	= (int)$this->post('issou','0');
		$sarr['fields'] = $this->post('fields');
		$sarr['attr']   = $this->post('attr');
		$sarr['xiaoshu']= (int)$this->post('xiaoshu','0'); //小数点
		$sarr['mid'] 	= $mid;
		$sarr['iszb'] 	= 0;
		if(substr($sarr['fieldstype'],0,6)=='change' && isempt($sarr['data'])){
			$sarr['data'] = ''.$sarr['fields'].'id';
		}
		
		include_once('webmain/main/flow/flowAction.php');
		$obj = new flowClassAction();
		$strs= $obj->elemensavefieldsbefore('flow_element', $sarr, $id);
		if($strs)return returnerror($strs);
	
		$where = '`id`='.$id.'';
		if($id==0)$where = '';
		$bo = m('flow_element')->record($sarr, $where);	
		
		
		if($bo)$obj->elemensavefields('', $sarr);
		
		//一键布局PC录入
		$path = 'webmain/flow/page/input_'.$num.'.html';
		$isscl = false;
		$xgwj = 0;
		$base = 0;
		if(!file_exists($path)){
			$isscl = true;
		}else{
			$ofile = file_get_contents($path);
			if(contain($ofile,'{file_content}'))$xgwj = 1;
			if(contain($ofile,'{base_name}'))$base = 1;
			if(contain($ofile,'autoyijianview'))$isscl = true;
		}
		if($isscl){
			$obj->yinruonearr = array(
				'modeid' => $mid,
				'xgwj'   => $xgwj,
				'base'   => $base,
			);
			$str = $obj->yinruoneAjax();
			$this->rock->createtxt($path, $str);
		}
		
		return returnsuccess();
	}
	
	//删除字段
	public function delziduanAction()
	{
		$id 	= (int)$this->get('id','0');
		m('flow_element')->delete($id);
		return returnsuccess();
	}
	
	//创建模块
	public function createmodeAction()
	{
		$name 		= $this->rock->xssrepstr($this->post('name'));
		$fields 	= c('pingyin')->get($name,1);
		if(!$fields || strlen($fields)<4)$fields = $this->db->ranknum('flow_set','num'); 
		if(strlen($fields)>20)$fields = substr($fields,0,18);
		$num 		= 'zz'.$fields.'';
		if(m('flow_set')->rows("`num`='$num'")>0){
			$fields = $this->db->ranknum('flow_set','num'); 
			$num 		= 'zz'.$fields.'';
		}
		$id 		= 0;
		$uarr['name'] = $name;
		$uarr['num']  = $num;
		$uarr['table']  = $num;
		$uarr['isflow']  = 0;
		$uarr['tables']  = '';
		$uarr['names']   = '';
		$uarr['sort']  	 = '0';
		$uarr['type']  	= '新增';
		$uarr['receid']  	= 'all';
		$uarr['recename']  = '全体人员';
		$uarr['optdt']  	= $this->now;
		$uarr['isup']  		= 0;
		$uarr['isscl']  	= 1;
		$uarr['pctx']  		= 1;
		$uarr['status']  	= 1;
		$uarr['istxset']  	= 0;
		$uarr['ispl']  	= 0;
		
		$id = m('flow_set')->insert($uarr);
		include_once('webmain/main/flow/flowAction.php');
		$obj = new flowClassAction();
		$obj->flowsetsaveafter('flow_set', $uarr);
		$obj->setinputid = $id;
		$obj->inputAction();
		$wherestr = $this->jm->base64encode('`optid`={uid}');
		m('flow_where')->insert(array(
			'setid' => $id,
			'num' => 'my',
			'name' => '我添加数据',
			'wheresstr' => $wherestr,
			'sort' => 0,
			'islb' => 1,
			'status' => 1,
		));
		
		m('flow_where')->insert(array(
			'setid' => $id,
			'num' => 'all',
			'pnum' => 'all',
			'name' => '所有数据',
			'wheresstr' => $this->jm->base64encode('1=1'),
			'sort' => 0,
			'islb' => 1,
			'status' => 1,
		));
		
		$uarr = array(
			'recename' => '全体人员',
			'receid' => 'all',
			'modeid' => $id,
			'type' => 2,
			'wherestr' => $wherestr,
		);
		m('flow_extent')->insert($uarr);
		$uarr['type'] = 3;
		m('flow_extent')->insert($uarr);
		
		m('mode')->createlistpage($id); //生成列表页
		
		return returnsuccess(array(
			'name' 	=> ''.$id.'.'.$name.'('.$num.')',
			'id' 	=> $id,
			'num'	=> $num
		));
	}
}