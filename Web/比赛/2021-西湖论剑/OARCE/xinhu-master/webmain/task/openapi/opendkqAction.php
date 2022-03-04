<?php
/**
*	将数据上传到打卡记录表上
* 	请求地址如：http://127.0.0.1/api.php?m=opendkq&openkey=key
*	请求方式：POST
*	提交过来数据[{"name":"姓名","dkdt":"2016-10-22 09:00:00"}]
*/
class opendkqClassAction extends openapiAction
{
	public function indexAction()
	{
		//6 接口导入
		$carr 	= $this->senddata(6);
		$this->showreturn('成功导入'.$carr['oi'].'条数据');
	}
	
	//
	private function senddata($type)
	{
		$str = $this->postdata;
		if(isempt($str))$this->showreturn('', 'not data', 201);
		$arr 	= json_decode($str, true);
		$oi 	= 0;$uarr = array();$finarr = array();
		$dtobj 	= c('date');$adb 	= m('admin');$db = m('kqdkjl');$uobj = m('userinfo');
		$updt 	= '';
		$cheobj = c('check');
		$snarr 	= array();
		if($type==9){
			$snarr = $this->db->getarr('[Q]kqjsn','`pinpai`=1','`id`,`name`','num');
		}
		$datype = array('密码','指纹','刷卡');
		
		if(is_array($arr))foreach($arr as $k=>$rs){
			$name = isset($rs['name']) ? $rs['name'] : '';
			$dkdt = isset($rs['dkdt']) ? $rs['dkdt'] : '';
			$finge= isset($rs['finge']) ? $rs['finge'] : '';
			$name = str_replace("'",'', $name);
	
			$uid  = 0;
			$snid = 0;
			$sntype = 1;
			$comid = 0;
			$explain = '';
			if($type==9){
				$sn	  = arrvalue($rs, 'sn');
				if(!$sn)continue;
				$snrs = arrvalue($snarr, $sn);
				if(!$snrs)continue;
				$snid = $snrs['id'];
				$explain  = '使用['.$snrs['name'].']打卡';
				if(!isset($snrs['isgx'])){
					m('kqjsn')->update("`lastdt`='$this->now'", $snid);
					$snarr[$sn]['isgx'] = 'a';
				}
			}
			if(!isempt($name) && !isempt($dkdt)){
				if(!$dtobj->isdate($dkdt))continue;
				if($updt=='' || $dkdt>$updt)$updt = $dkdt;
				
				if($type==9 && $finge){
					if(isset($finarr[$finge])){
						if($finarr[$finge]){
							$uid 	= $finarr[$finge]['id'];
							$comid	= $finarr[$finge]['companyid'];
						}
					}else{
						$uwher  = "`finger`='$finge'";
						$usobj	= $uobj->getrows($uwher,'`id`,`companyid`');
						if($usobj){
							$uid	= $usobj[0]['id'];
							$comid	= $usobj[0]['companyid'];
							$finarr[$finge] = $usobj[0];
						}else{
							$finarr[$finge] = false;
						}
					}
				}
				
				if($uid==0){
					if(isset($uarr[$name])){
						if($uarr[$name]){
							$uid 	= $uarr[$name]['id'];
							$comid	= $uarr[$name]['companyid'];
						}
					}else{
						$uwher  = "`name`='$name'";
						if($cheobj->iscnmobile($name))$uwher  = "`mobile`='$name'";
						if($cheobj->isemail($name))$uwher  	  = "`email`='$name'";
						
						$usar 	= $adb->getrows($uwher,'`id`,`companyid`');
						if($usar){
							$uid	= $usar[0]['id'];
							$comid	= $usar[0]['companyid'];
							$uarr[$name] = $usar[0];
						}else{
							$uarr[$name] = false;
						}
					}
				}
				if($uid==0)continue;
				if($comid==0)$comid = 1;
				if($db->rows("`uid`='$uid' and `dkdt`='$dkdt'")>0)continue;
				$oi++;
				$db->insert(array(
					'uid'	=> $uid,
					'dkdt'	=> $dkdt,
					'optdt'	=> $this->now,
					'type'	=> $type,
					'snid'	=> $snid,
					'sntype'=> $sntype,
					'explain'=> $explain,
					'comid'=> $comid,
				));
			}
		}
		if($updt && $updt>$this->now)$updt=$this->now;
		//$this->rock->debugs(array($uarr, $finarr),'daorudaka');
		return array(
			'oi' 	=> $oi,
			'updt' 	=> $updt,
		);
	}
	
	/**
	*	中控考勤机导入
	*/
	public function zktimeAction()
	{
		//9中控
		$carr 	= $this->senddata(9);
		echo $carr['updt'];
	}
}