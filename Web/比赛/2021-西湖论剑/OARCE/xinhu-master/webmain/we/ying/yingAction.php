<?php 
/**
*	移动端应用控制器页面
*	主页：http://www.rockoa.com/
*	软件：信呼
*	作者：雨中磐石(rainrock)
*	日期：2016-10-10
*/

class yingClassAction extends ActionNot{
	
	public $openfrom = '';
	
	public function initAction()
	{
		$this->mweblogin(0, true);
		$openfrom = $this->get('openfrom');
		if(isempt($openfrom))$openfrom = $this->get('cfrom');
		if(!isempt($openfrom)){
			$this->rock->setsession('openfrom', $openfrom);
		}else{
			$openfrom = $this->rock->session('openfrom');
		}
		$this->openfrom = $openfrom;
		$this->assign('openfrom', $this->openfrom);
	}
	
	private function bd6($str)
	{
		return $this->jm->base64decode($str);
	}
	
	public function defaultAction()
	{
		$ybarr	 = $this->option->authercheck();
		if(is_string($ybarr))return $ybarr;
		$authkey = $ybarr['authkey'];
		$num 	 = $this->get('num');
		$mnum 	 = $this->get('mnum'); //模块编号
		$this->assign('xhauthkey', getconfig('authkey', $authkey));
		if(!isempt($mnum)){
			$this->showmodenum($mnum);
			return;
		}
		$reim	 = m('reim');
		$arr 	 = $reim->getagent(0, "and `num`='$num'");
		if(!$arr)exit('应用['.$num.']不存在');
		$rs  = $arr[0];
		$this->title = $rs['name'];
		$yyurl 	= ''.P.'/we/ying/yingyong/'.$num.'.html';
		if(!file_exists($yyurl))$yyurl='';
		$yyurljs 	= ''.P.'/we/ying/yingyong/'.$num.'.js';
		if(!file_exists($yyurljs))$yyurljs='';
		

		$rs['iscy'] = $this->iscy($num);
		$this->assign('arr', $rs);
		$this->assign('num', $num);
		
		
		$this->assign('yyurl', $yyurl);
		$this->assign('yyurljs', $yyurljs);
		$this->assign('searchmsg', '输入关键词搜索');
		$this->assign('typename', '');
		$gid 	= $rs['id'];
		$reim->setallyd('agent', $this->adminid, $gid);

		$clasne 	= 'ying_'.$num.'Class';
		$classpath  = ''.P.'/we/ying/yingyong/'.$clasne.'.php';
		if(file_exists($classpath)){
			include_once($classpath);
			$yingobj = new $clasne();
			$yingobj->initYing($this);
		}
		if(getconfig('useropt')=='1')m('log')->addlog('打开应用', '应用['.$num.'.'.$this->title.']');
	}
	
	//默认根据模块显示
	private function showmodenum($mnum)
	{
		$typename = '';
		$flow   = m('flow')->initflow($mnum);
		$mrs 	= $flow->moders;
		
		if($mrs['status']=='0')exit('模块['.$mnum.','.$mrs['name'].']已停用');
		$souarr		 = $flow->flowwesearchdata(0);
		$searchmsg	 = arrvalue($souarr, 'searchmsg','输入关键词搜索');
		$typename	 = arrvalue($souarr, 'typename');
		
		$this->title = $mrs['name'];
		$pnum 	= $this->get('pnum');
		$menu	= array();
		$atypearr = m('where')->getmywhere($mrs['id'], $this->adminid, $pnum);
		if(!$atypearr)exit('请到【流程模块→流程模块条件】建条件，分组编号要为空');
		if(isempt($pnum)){
			if($mrs['iscs']>0)$atypearr[] = array('id'	=> 0,'num'	=> 'chaos','name'  => '抄送给我');
			if($mrs['isflow']>0)$atypearr[] = array('id'	=> 0,'num'	=> 'mychuli','name'  => '经我处理');
		}
		$isadd = m('view')->isadd($mrs['id'], $this->adminid);
		if($isadd)$atypearr[] = array('id'	=> 0,'num'	=> 'add','type'	=> 1,'name'  => '＋新增');
		foreach($atypearr as $k1=>$rs1){
			$uar 	= array('type' => 0,'name' => $rs1['name'],'url' => $rs1['num'].'|'.$mnum.'','num' => '','submenu'=> array());
			if(arrvalue($rs1,'type')==1){$uar['type']=1;$uar['url']='add_'.$mnum.'';}
			$menu[] = $uar;
			if($k1>1)break;
		}
		if(count($atypearr)>3){
			$submenu = array();
			foreach($atypearr as $k1=>$rs1){
				$uar = array('type' => 0,'name' => $rs1['name'],'url' => $rs1['num'].'|'.$mnum.'','num' => '','submenu'=> array());
				if(arrvalue($rs1,'type')==1){$uar['type']=1;$uar['url']='add_'.$mnum.'';}
				if($k1>1)$submenu[] = $uar;
			}
			$menu[2] = array(
				'name' => '更多&gt;&gt;',
				'num' => '',
				'submenu' => $submenu,
			);
		}
		if(!$menu)$menu[] = array(
			'name' => $mrs['name'],
			'url' => 'my|'.$mnum.'',
			'num' => '',
			'submenu' => array(),
		);
		
		$arr	= array(
			'face' => '',
			'leixing'=>$mnum,
			'menu' => $menu,
			'num'	=> 'base',
			'name'	=> $mrs['name'],
		);
		$yyurl = '';
		$yyurljs = '';
		$this->assign('searchmsg', $searchmsg);
		$this->assign('yyurl', $yyurl);
		$this->assign('arr', $arr);
		$this->assign('yyurljs', $yyurljs);
		$this->assign('typename', $typename);
		if(getconfig('useropt')=='1')m('log')->addlog('打开模块应用', '模块['.$mnum.'.'.$this->title.']');
	}
	
	
	
	private function iscy($num)
	{
		$myyyid= $this->option->getval('yinghomeshow_'.$this->adminid.'');
		$iscy  = 0;
		if(!isempt($myyyid) && contain(','.$myyyid.',',','.$num.','))$iscy=1;
		return $iscy;
	}
	
	public function locationAction()
	{
		$this->title = '考勤定位';
		$kq 	= m('kaoqin');
		$arr 	= m('waichu')->getoutrows($this->date,$this->adminid);
		$this->assign('rows', $arr);
		$dt 	= $this->rock->date;
		$dwarr	= m('location')->getrows("uid='$this->adminid' and `optdt` like '$dt%'",'*','`id` desc');
		$this->assign('dwarr', $dwarr);
		$kqrs 	= $kq->dwdkrs($this->adminid, $this->date);
		$isgzh	= m('wxgzh:index')->isusegzh();
		$this->assign('isgzh', $isgzh);
		$this->assign('kqrs', $kqrs);
		$dwids	= arrvalue($kqrs, 'dwids');
		$kqors	= array();
		if(!isempt($dwids)){
			$kqors = m('kqdw')->getrows("id in($dwids) and `id`<>".$kqrs['id']."");
		}
		$this->assign('kqors', $kqors);
		$this->smartydata['qqmapkey']	= getconfig('qqmapkey','55QBZ-JGYLO-BALWX-SZE4H-5SV5K-JCFV7');
	}
	
	/**
	*	最新打卡使用
	*/
	public function dakaAction()
	{
		$this->title = '考勤打卡';
		
		$kq 	= m('kaoqin');
		$dt 	= $this->rock->date;
		$dwarr	= m('location')->getrows("uid='$this->adminid' and `optdt` like '$dt%'",'*','`id` desc');
		$this->assign('dwarr', $dwarr);
		$kqrs 	= $kq->dwdkrs($this->adminid, $this->date);
		$isgzh	= m('wxgzh:index')->isusegzh();
		$this->assign('isgzh', $isgzh);
		$this->assign('iscy', $this->iscy('kqdaka'));
		$this->assign('kqrs', $kqrs);
		$dwids	= arrvalue($kqrs, 'dwids');
		$kqors	= array();
		if(!isempt($dwids)){
			$kqors = m('kqdw')->getrows("id in($dwids) and `id`<>".$kqrs['id']."");
		}
		$this->assign('kqors', $kqors);
		$this->smartydata['qqmapkey']	= getconfig('qqmapkey','55QBZ-JGYLO-BALWX-SZE4H-5SV5K-JCFV7');
	}
}