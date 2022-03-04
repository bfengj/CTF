<?php
class flowoptClassAction extends Action
{
	public function addlogAjax()
	{
		$sm 	= $this->post('sm');
		$mid 	= (int)$this->post('mid');
		$modenum= $this->post('modenum');
		$name 	= $this->post('name');
		$zt 	= $this->post('zt');
		$ztid 	= $this->post('ztid','1');
		$ztcolor= $this->post('ztcolor');
		m('flow')->addlog($modenum, $mid,$name,array(
			'explain' 		=> $sm,
			'statusname' 	=> $zt,
			'status' 		=> $ztid,
			'color' 		=> $ztcolor
		));
		$this->showreturn('ok');
	}
	
	public function delflowAjax()
	{
		$mid 	= (int)$this->post('mid');
		$modenum= $this->post('modenum');
		$sm 	= $this->post('sm');
		$msg 	= m('flow')->opt('deletebill', $modenum, $mid, $sm);
		if($msg != 'ok')$this->showreturn('', $msg, 201);
		$this->showreturn('ok');
	}
	
	//审核
	public function checkAjax()
	{
		$mid 	= (int)$this->post('mid');
		$zt 	= (int)$this->post('zt');
		$modenum= $this->post('modenum');
		$sm 	= $this->post('sm');
		$msg 	= m('flow')->opt('check', $modenum, $mid, $zt, $sm);
		if($msg=='ok'){
			return returnsuccess('处理成功');
		}else{
			return returnerror($msg);
		}
	}
	
	//单据获取操作菜单
	public function getoptnumAjax()
	{
		$mid 	= (int)$this->post('mid');
		$num	= $this->post('num');
		
		$arr 	= m('flow')->getoptmenu($num, $mid, 0);
		$this->showreturn($arr);
	}
	
	public function yyoptmenuAjax()
	{
		$num 	= $this->post('modenum');
		$sm 	= $this->post('sm');
		$optid 	= (int)$this->post('optmenuid');
		$zt 	= (int)$this->post('statusvalue');
		$mid 	= (int)$this->post('mid');
		$msg 	= m('flow')->optmenu($num, $mid, $optid, $zt, $sm);
		if($msg != 'ok')$this->showreturn('', $msg, 201);
		$this->showreturn('');
	}
	
	public function chehuiAjax()
	{
		$mid 	= (int)$this->post('mid');
		$modenum= $this->post('modenum');
		$sm 	= $this->post('sm');
		$msg 	= m('flow')->opt('chehui', $modenum, $mid, $sm);
		if($msg != 'ok')$this->showreturn('', $msg, 201);
		$this->showreturn('');
	}
	
	/**
	*	模块统计
	*/
	public function flowtotalAjax()
	{
		$modenum= $this->post('modenum');
		$rows 	= m('flow')->initflow($modenum)->flowtotal();
		$barr['rows'] = $rows;
		echo json_encode($barr);
	}
	
	/**
	*	将异常单据标识已完成
	*/
	public function oksuccessAjax()
	{
		$mid 	= (int)$this->post('mid');
		$modenum= $this->post('modenum');
		$sm 	= $this->post('sm');
		$lx 	= $this->post('lx','1');
		$flow   = m('flow')->initflow($modenum, $mid);
		$msg 	= $flow->checkerror($lx, $sm);
		return $msg;
	}
	
	
	/**
	*	引用签名图片
	*/
	public function qianyinAjax()
	{
		$path = $this->option->getval('qmimgstr_'.$this->adminid.'');
		if(isempt($path))return returnerror('你并没有设置签名图片，可到[个人设置]下添加签名图片');
		if(!file_exists($path)){
			return returnerror('签名图片不存在了，可到[个人设置]下重新设置签名图片');
		}
	
		return returnsuccess($path);
	}
	
	/**
	*	回执确认
	*/
	public function receiptcheckAjax()
	{
		$mid 		= (int)$this->post('mid');
		$modenum	= $this->post('modenum');
		$sm 		= $this->post('sm');
		$receiptid 	= (int)$this->post('receiptid','0');
		$flow 		= m('flow')->initflow($modenum, $mid);
		
		return $flow->receiptcheck($receiptid, $sm);
	}
	
	/**
	*	读取字段
	*/
	public function getfieldsAjax()
	{
		$modenum	= $this->get('modenum');
		$flow 		= m('flow')->initflow($modenum);
		$farr 		= array();
		foreach($flow->fieldsarra as $k=>$rs){
			$farr[] = array(
				'fields' => $rs['fields'],
				'name' 	=> $rs['name'],
				'islb' 	=> $rs['islb'],
			);
		}
		return array(
			'fieldsarr' => $farr,
			'isflow'	=> $flow->isflow,
			'modenames'	=> $flow->moders['names'],
		);
	}
	
	/**
	*	提交评论
	*/
	public function pinglunAjax()
	{
		$sm 	= $this->post('sm');
		$mid 	= (int)$this->post('mid');
		$modenum= $this->post('modenum');
		$flow 	= m('flow')->initflow($modenum, $mid);
		$flow->optmenu(-15,0, $sm);
		$this->showreturn('ok');
	}
	
	public function savetopdfAjax()
	{
		$imgbase64 = $this->post('imgbase64');
		if(isempt($imgbase64))return returnerror('无数据');
		$path = ''.UPDIR.'/logs/'.date('Y-m').'/abc.png';
		$bo = $this->rock->createtxt($path, base64_decode($imgbase64));
		if(!$bo)return returnerror(''.UPDIR.'目录无写入权限');
		
		$pa1 = ''.ROOT_PATH.'/include/fpdf/fpdf.php';
		if(!file_exists($pa1))return returnerror('没有安装fpdf插件');
		include_once($pa1);
		
		$fpdf = new FPDF();
		$fpdf->AddPage();
		$fpdf->Image($path,0,0);
		
		$fpdf->Output('F',''.UPDIR.'/logs/'.date('Y-m').'/to.pdf');
		$this->showreturn('ok:'.$fpdf->GetPageHeight().'');
	}
	
	
	/**
	*	获取修改记录
	*/
	public function editcontAjax()
	{
		$mid 	= (int)$this->get('mid');
		$modenum= $this->get('modenum');
		$optdt 	= (int)$this->get('optdt');
		$uid 	= (int)$this->get('uid');
		$db 	= m('edit');
		$optdt1 = date('Y-m-d H:i:s', $optdt);
		$table	= m('mode')->getmou('`table`',"`num`='$modenum'");
		$rows 	= $db->getall("`table`='$table' and `mid`=$mid and `optid`=$uid and `optdt`='$optdt1'",'*','`id` asc');
		if(!$rows)return '无修改记录';
		
		return c('html')->createrows($rows,'fieldsname,字段,left@oldval,原来值,left@newval,新值,left','#888888');
	}
}