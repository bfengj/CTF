<?php 
//保存打卡记录等
class weixinClassAction extends apiAction{

	/**
	*	获取jssdk签名
	*/
	public function getsignAction()
	{
		$num 	= 'weixin_corpid';
		$isqywx	= false;
		$appId	= $this->option->getval($num);
		if(isempt($appId) || $this->rock->isqywx){
			$isqywx = true;
			$num 	= 'weixinqy_corpid';
		}
		
		if(isempt($this->option->getval($num))){
			$arr['appId'] = '';
		}else{
			$url = $this->getvals('url');
			if($isqywx){
				$agentid = $this->rock->post('agentid', $this->getsession('wxqyagentid'));
				if(isempt($agentid)){
					$arr['appId'] = '';
				}else{
					$arr = m('weixinqy:signjssdk')->getsignsdk($url, $agentid);
				}
			}else{
				$arr = m('weixin:signjssdk')->getsignsdk($url);
			}
		}
		$this->showreturn($arr);
	}
	
	public function addlocationAction()
	{
		$fileid 			= (int)$this->post('fileid','0');
		$imgpath			= m('file')->getmou('filepath', $fileid);
		$now 				= $this->rock->now;
		$uid				= $this->adminid;
		$comid				= m('admin')->getcompanyid($uid);
		$type 				= (int)$this->post('type');
		$arr['location_x']	= $this->post('location_x');
		$arr['location_y']	= $this->post('location_y');
		$arr['scale']		= (int)$this->post('scale');
		$arr['precision']	= (int)$this->post('precision');
		$arr['label']		= $this->getvals('label');
		$arr['explain']		= $this->getvals('sm');
		$arr['optdt']		= $now;
		$arr['uid']			= $uid;
		$arr['comid']		= $comid;
		$arr['imgpath']		= $imgpath;
		m('location')->insert($arr);
		if($type==1){
			$dkdt 	= $now;
			$ip		= $this->rock->ip;
			$this->db->record('[Q]kqdkjl',array(
				'dkdt' 	=> $dkdt,
				'uid'	=> $uid,
				'optdt'	=> $now,
				'imgpath'	=> $imgpath,
				'address'	=> $arr['label'],
				'comid'		=> $comid,
				'lat'=> $arr['location_x'],
				'lng'=> $arr['location_y'],
				'accuracy'=> $arr['precision'],
				'explain'=> $arr['explain'],
				'ip'	=> $ip,
				'type'	=> 2
			));
			$dt = substr($dkdt, 0, 10);
			m('kaoqin')->kqanay($uid, $dt);
		}
		$this->showreturn(array('now'=>$now));
	}
	
	/**
	*	获取媒体文件
	*/
	public function getmediaAction()
	{
		$media_id = $this->post('media_id');
		$type 	  = $this->post('type');
		$barr 	  = m('weixinqy:media')->downmedia($media_id, $type);
		if($barr['errcode']!=0)return returnerror($barr['msg']);
		return returnsuccess($barr);
	}
}