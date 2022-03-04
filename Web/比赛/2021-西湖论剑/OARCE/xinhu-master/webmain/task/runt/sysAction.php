<?php
class sysClassAction extends runtAction
{
	//数据备份
	public function beifenAction()
	{
		if(getconfig('systype')=='demo')return 'success';
		m('beifen')->start();
		$this->todoarr	= array(
			'title' 	=> '数据库备份',
			'cont' 		=> '数据库在['.$this->now.']备份了。',
		);
		return 'success';
	}
	public function upgtxAction()
	{
		$xinhu	= c('xinhu');
		$db 	= m('chargems');
		$lastdt	= strtotime($this->runrs['lastdt']);
		$barr	= $xinhu->getdata('modeupg', array('lastdt'=>$lastdt));
		if($barr['code']!=200)exit($barr['msg']);
		$str 	= '';
		foreach($barr['data'] as $k=>$rs){
			$id = $rs['id'];
			$na = $rs['name'];
			$state  = 0;
			$ors 	= $db->getone("`type`=0 and `mid`='$id'");
			if($ors){
				$state = 1;
				if($rs['updatedt']>$ors['updatedt'])$state=2;
			}
			if($state==0)$str.='模块['.$na.']可安装;';
			if($state==2)$str.='模块['.$na.']可<font color=red>升级</font>;';
		}
		if($str!=''){
			$this->todoarr	= array(
				'title' 	=> '安装升级',
				'cont' 		=> $str.'请到[系统→系统工具→系统升级]下处理',
			);
		}
		return 'success';
	}
	
	
	//数据更新,更新用户的
	//命令就是：php task.php sys,dataup -runid=6
	public function dataupAction()
	{
		m('admin')->updateinfo(); //更新人员
		m('imgroup')->updategall(); //更新会话组
		$reim 	= m('reim');
		if($reim->installwx(1))m('weixinqy:user')->getuserlist();
		return 'success';
	}
	
	/**
	*	清理数据
	*/
	public function clearAction()
	{
		$date1 	= date('Y-m-d', time()-30*24*3600); //30天前
		$date2 	= date('Y-m-d', time()-6*30*24*3600); //半年前
		$date3 	= date('Y-m-d', time()-3*30*24*3600); //3个月
		$month3 	= date('Y-m', time()-3*30*24*3600); //3个月
		$kqclear	= (int)$this->option->getval('kqcleartime','0');
		$alltabls 	= $this->db->getalltable();
		if($kqclear>0){
			$date4 = date('Y-m-d', time()-$kqclear*30*24*3600);
			if(in_array(''.PREFIX.'kqdkjl', $alltabls))
				m('kqdkjl')->delete("`dkdt`<='$date4 23:59:9'"); //打卡记录
		}
		m('log')->delete("`optdt`<'$date3 23:59:59'"); // 日志3个月
		
		m('logintoken')->delete("`moddt`<'$date1 23:59:59'"); // token1个月
		
		if(in_array(''.PREFIX.'kqjcmd', $alltabls))
			m('kqjcmd')->delete("`optdt`<'$date1 23:59:59'");  //考勤机命令
		
		if(in_array(''.PREFIX.'kqanay', $alltabls))
			m('kqanay')->delete("`dt`<'$date3'"); //考勤分析
		
		if(in_array(''.PREFIX.'dailyfx', $alltabls))
			m('dailyfx')->delete("`month`<'$month3'"); //日志分析
		
		//更多清理自己添加
		
		return 'success';
	}
}