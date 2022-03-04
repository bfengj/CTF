<?php
/**
*	应用上的接口文件，读取数据显示
*/
class agentModel extends Model
{
	public $agentnum	= '';
	public $agentid		= '0';
	public $modeid		= 3;
	public $page		= 1;
	public $loadci		= 1;
	public $limit		= 10; //默认加载的条数
	public $user_id		= 0;
	public $event		= '';
	public $agentrs;
	public $moders;
	public $flow		= null;
	
	public function getdatas($uid, $lx, $p){}
	
	/**
	*	从新接口方法
	*	$rows 要展示数据 $rowd 原始数据
	*/
	protected function agentrows($rows, $rowd, $uid){return $rows;}
	protected function agenttotals($uid){return array();}
	protected function agentdata($uid, $lx){return false;}
	
	protected $showuface	= true;//是否显示对应人员头像
	
	
	public function gettotal()
	{
		return array(
			'stotal' => 0,
			'titles' => ''
		);
	}
	
	public function getagentinfor($num)
	{
		$this->agentnum = $num;
		$this->agentrs	= m('im_group')->getone("`num`='$num'");
		$this->moders	= m('flow_set')->getone("`num`='$num'");
		if($this->agentrs){
			$this->agentid = $this->agentrs['id'];
		}
		if($this->moders){
			$this->modeid 	= $this->moders['id'];
			$this->flow 	= m('flow')->initflow($num);
		}
	}
	
	
	public function getdata($uid, $num, $lx, $page)
	{
		if(!isempt($lx)){
			$lxa = explode('|', $lx);
			if(isset($lxa[1]) && !isempt($lxa[1])){
				$num = $lxa[1];
				$lx  = $lxa[0];
			}
		}
		$this->loadci 	= (int)$this->rock->get('loadci','0');
		$this->getagentinfor($num);
		$this->page 	= $page;
		$this->user_id 	= $uid;
		$this->event 	= $lx;
		$narr 	= $this->agentdata($uid, $lx);
		$lx		= $this->event;
		if(!$narr)$narr = $this->getdatalimit($uid, $lx);
		$arr 	= array(
			'wdtotal' 	=> 0,
			'event'		=> $lx,
			'num'		=> $num,
			'rows'		=> array(),
			'rowd'		=> array(),
			'page'		=> $page,
			'limit'		=> $this->limit,
			'agentid'	=> $this->agentid,
			'count'		=> 0,
			'maxpage'	=> 0
		);
		if(is_array($narr))foreach($narr as $k=>$v)$arr[$k]=$v;
		$barr 			= $this->agentrows($arr['rows'],$arr['rowd'], $uid);
		if(isset($barr['rows'])){
			foreach($barr as $k=>$v)$arr[$k]=$v;
			$barr = $barr['rows'];
		}
		$arr['rows'] 	= $this->showrowsface($barr);
		$arr['stotal']	= $this->agenttotals($uid);
		unset($arr['rowd']);
		return $arr;
	}
	
	//状态切换读取一般用于有流程
	protected function agentrows_status($rows, $rowd){
		foreach($rowd as $k=>$rs){
			if($this->flow){
				$rows[$k]['modename'] 	= $this->moders['name'];
				$rows[$k]['modenum'] 	= $this->moders['num'];
				if(isset($rs['status'])){
					$zts = $this->flow->getstatus($rs);
					$rows[$k]['statustext'] 	= $zts[0];
					$rows[$k]['statuscolor'] 	= $zts[1];
					if($rs['status']==5)$rows[$k]['ishui'] = 1;
				}
			}
		}
		return $rows;
	}
	
	/**
	*	应用上获取数据
	*/
	public function getdatalimit($uid, $lx)
	{
		if(!$this->flow)return array();
		$nas 	= $this->flow->billwhere($uid, $lx);
		$_wehs	= $nas['where'];
		
		$where 	= '1=1 '.$_wehs.'';
		$fields = '*';
		$order  = '';
		$_tabsk 	= $nas['table'];
		if(contain($_tabsk,' ')){
			$tables	= $_tabsk;
			$table  = $this->flow->mtable;
		}else{
			$table	= $_tabsk;
			$tables = $this->rock->T($table);
		}
		if(!isempt($nas['order']))$order 	= $nas['order'];
		if(!isempt($nas['fields']))$fields 	= $nas['fields'];
		$arr 	= m($table)->getlimit($where, $this->page, $fields, $order, $this->limit, $tables);
		$rows 	= $arr['rows'];
		$row 	= array();
		$summarx= $this->flow->moders['summarx'];
		if(isempt($summarx))$summarx = 'cont:'.$this->flow->moders['summary'].'';
		$suarr  = $this->zhaiyaoar($summarx);
		$rows 	= $this->flow->viewjinfields($rows);//禁看字段处理
		foreach($rows as $k=>$rs){
			$jarr 	= array();
			$rs 	= $this->flow->flowrsreplace($rs, 2);
			if(isset($rs['id']))$jarr['id']	  		= $rs['id'];	
			if(isset($rs['uid']))$jarr['uid'] 		= $rs['uid'];
			if(isset($rs['ishui']))$jarr['ishui'] 	= $rs['ishui'];
			foreach($suarr as $f=>$nr){
				$str  		= $this->rock->reparr($nr, $rs);
				if($f=='cont')$str = $this->contreplaces($str);
				$jarr[$f] 	= $str;
			}
			$rows[$k] 	= $rs;
			$row[]  	= $this->flow->flowrsreplace_we($jarr, $rs);
		}
		$arr['rows'] 	= $row;
		$arr['rowd'] 	= $rows;
		return $arr;
	}
	
	private function zhaiyaoar($str)
	{
		$stra = explode("\n", $str);
		$arr  = array();
		foreach($stra as $nr){
			if(strpos($nr,'title:')===0)$arr['title'] = substr($nr, 6);
			if(strpos($nr,'optdt:')===0)$arr['optdt'] = substr($nr, 6);
			if(strpos($nr,'cont:')===0)$arr['cont'] = substr($nr, 5);
		}
		if(!$arr)$arr['cont'] = $str;
		return $arr;
	}
	
	private function contreplaces($str)
	{
		$stra 	= explode('[br]', $str);
		$s1 	= '';
		foreach($stra as $s){
			$a1 = explode('：', $s);
			if(isset($a1[1]) && $a1[1]==''){
			}else{
				$s1.='$%#'.$s.'';
			}
		}
		if($s1!=''){
			$s1 = str_replace('$%#', "\n", substr($s1, 3));
		}
		return $s1;
	}
	
	/**
	*	显示人员对应头像
	*/
	private function showrowsface($rows)
	{
		if(!$this->showuface)return $rows;
		$uids	= '0';
		foreach($rows as $k=>$rs){
			if(isset($rs['uid']))$uids		.=','.$rs['uid'].'';
		}
		if($uids!='0'){
			$adb	= m('admin');
			$uarr 	= $this->db->getarr('[Q]admin','id in('.$uids.')','`face`,`name`');
			foreach($rows as $k=>$rs){
				if(!isset($rs['uid']))continue;
				if(isset($uarr[$rs['uid']])){
					$rows[$k]['face'] = $adb->getface($uarr[$rs['uid']]['face']);
				}
			}
		}
		return $rows;
	}
}