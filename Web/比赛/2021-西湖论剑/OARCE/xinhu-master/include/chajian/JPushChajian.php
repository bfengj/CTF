<?php 
/**
* 	最新系统推送1.9.7后
*	软件：信呼OA
*	最后更新：2021-10-09
*/
class JPushChajian extends Chajian{


	//-------------最新原生app推送app是1.2.3版本 和 最新app+---------------
	public function push($title, $desc, $cont, $palias)
	{
		
		$alias		= $palias['alias'];
		$xmalias	= $palias['xmalias']; //小米的
		$newalias	= $palias['newalias']; //最新使用的
		$oldalias	= $palias['oldalias']; //一般自己编译
		$uids		= $palias['uids'];
		$alias2019	= $palias['alias2019'];
		$pushuids	= $palias['pushuids']; //可以推送的用户ID
		$xmpush		= c('xmpush');
		$hwpush		= c('hwpush');
		
		//可推送判断
		$ketualia	= array();
		foreach($alias2019 as $ali1){
			$ali1aa = explode('|', $ali1);
			$_uid	= $ali1aa[2];
			if(in_array($_uid, $pushuids))$ketualia[] = $ali1;
		}
		$alias2019 = $ketualia;
		
		
		//$this->rock->debugs($palias,'pushalias');//判断能不能推送，打印这个
		
		$xharr = array(
			'uids'  => $uids,
			'title' => $this->rock->jm->base64encode($title),
			'cont'  => $this->rock->jm->base64encode($cont),
			'desc'  => $desc,
			'systype'=> getconfig('systype')
		);
		$isuguanw 	= false;
		

		//没有设置推送(走的信呼官网渠道)
		if(!$xmpush->sendbool() && !$hwpush->sendbool()){
			if($xmalias || $newalias || $oldalias || $alias2019){
				if($xmalias)$xharr['xmalias'] 		= join(',', $xmalias);
				if($newalias)$xharr['newalias'] 	= join(',', $newalias);
				if($oldalias)$xharr['oldalias'] 	= join(',', $oldalias);
				if($alias2019)$xharr['alias2019']	= join(',', $alias2019);
				$isuguanw = true;
			}
		}else{
			$desc = $this->rock->jm->base64decode($desc);
			$xmarr = array();//小米的人员
			$othar = array();//其他人用
			$iosar = array(); //IOS
			$hwarr = array(); //华为
			$iospas= array();
			if($alias2019)foreach($alias2019 as $ali1){
				$ali1aa = explode('|', $ali1);
				$regid  = $ali1aa[0];
				$sjlxx  = $ali1aa[1];
				if(contain($sjlxx,'xiaomi')){
					$xmarr[] = $regid;
				}else if(contain($sjlxx,'huawei')){
					if(isset($ali1aa[3]) && $ali1aa[3])$hwarr[] = $ali1aa[3];
				}else if(contain($sjlxx,'iphone')){	
					$iosar[] = $regid;
					$iospas[]= $ali1;
				}else{
					$othar[] = $regid;
				}
			}
			$msg = $msg1 = $msg2 = '';
			if($oldalias)$msg = $xmpush->androidsend($oldalias, $title, $desc, $cont);
			if($xmarr)$msg = $xmpush->androidsend($xmarr, $title, $desc);
			if($iosar){
				if(!$xmpush->jpushiosbool()){
					$xharr['alias2019']	= join(',', $iospas);
					$isuguanw = true;	
				}else{
					$msg1= $xmpush->jpushiossend($iosar, $title, $desc);
				}
			}
			if($hwarr)$msg2= $hwpush->androidsend($hwarr, $title, $desc);
			$msg5 = $msg.$msg1.$msg2;
			if($msg5)$this->rock->debugs($msg5,'mypush');
		}
		
		if($isuguanw){
			$runurl = c('xinhu')->geturlstr('jpushplat', $xharr);
			c('curl')->getcurl($runurl);
		}
	}
}