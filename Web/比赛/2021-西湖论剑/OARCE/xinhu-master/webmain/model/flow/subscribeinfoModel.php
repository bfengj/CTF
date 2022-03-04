<?php
//订阅的
class flow_subscribeinfoClassModel extends flowModel
{
	
	
	
	public function flowrsreplace($rs, $lx=0)
	{
		$filepath = $rs['filepath'];
		if(!file_exists(iconv('utf-8','gb2312',$filepath))){
			$filepath 	= '文件不存在了';
			$rs['ishui']= 1;
		}else{
			$filepath	= '<a href="'.URL.''.$filepath.'"';
			if(!$this->rock->ismobile())$filepath.=' target="_blank"';
			$filepath	.= '>打开</a>';
		}
		$rs['filepath'] = $filepath;
		return $rs;
	}

	
	/**
	*	订阅运行发送
	*/
	public function subscribe($id, $nowid=0, $receid='', $recename='')
	{
		$rs 		= $this->db->getone('[Q]subscribe','`id`='.$id.'');
		if(!$rs)return returnerror('不存在');
		
		if($rs['status']=='0')return returnerror('未启用不能运行');
		$title 		= $rs['title']; //订阅标题
		$cont 		= $rs['cont'];	//提醒内容
		$suburl 	= $this->rock->jm->base64decode($rs['suburl']);
		$suburlpost = $this->rock->jm->base64decode($rs['suburlpost']);
		if(isempt($suburl) || isempt($suburlpost))return;
		$base 		= m('base');
		
		$suburlpost	= $base->strreplace($suburlpost,0,1);
		$title	= $base->strreplace($title,0,1);
		$cont	= $base->strreplace($cont,0,1);
		
		$url 	= getconfig('localurl', URL).$suburl;
		//请求参数处理
		$params = array();
		$canarr	= explode('&', $suburlpost);
		foreach($canarr as $csnstr){
			$nets = explode('=', $csnstr);
			$params[$nets[0]] = $nets[1];
		}
		
		if($nowid==0)$nowid			= $rs['optid'];
		
		$params['exceltitle']		= $title;
		$params['exceltype']		= 'html';
		$params['tablename_abc'] 	= $this->rock->jm->encrypt($params['tablename_abc']);
		$params['exceltitle'] 		= $this->rock->jm->encrypt($params['exceltitle']);
		$params['excelfields'] 		= $this->rock->jm->encrypt($params['excelfields']);
		$params['excelheader'] 		= $this->rock->jm->encrypt($params['excelheader']);
		
		//传个让不需要登录验证
		$subscribe_post 			= 'subscribe_'.time().'';
		$subscribe_key				= md5($url.time().$subscribe_post); 
		$params['subscribe_key']	= $subscribe_key;
		$params['subscribe_post']	= $subscribe_post;
		$params['subscribe_adminid']= $nowid;
		$this->option->setval('subscribe_key', $subscribe_key);
	
		$bstr 	= c('curl')->postcurl($url, $params);
		$this->db->update('[Q]subscribe',"`lastdt`='{$this->rock->now}'", '`id`='.$id.'');
	
		if(!isempt($bstr) && substr($bstr, 0, 1)=='{'){
			$publicurl	= $this->rock->getouturl();//公网URL
			$barr 		= json_decode($bstr, true);
			$htmlpath 	= $barr['url'];
			
			if($barr['downCount']==0)return returnerror('记录数为0,没有可订阅数据');
			
			$url 		= $publicurl.$htmlpath;
			//发送提醒
			if(isempt($receid)){
				$receid 	= $rs['optid'];
				$recename 	= $rs['optname'];
			}
			
			$newid = $this->insert(array(
				'mid' 		=> $id,
				'receid' 	=> $receid,
				'recename' 	=> $recename,
				'title' 	=> $title,
				'cont' 		=> $cont,
				'optdt' 	=> $this->rock->now,
				'filepath' 	=> $htmlpath,
			));
			
			//推送提醒
			$receid = $this->pushs($receid, $cont,$title,array(
				'id' => $newid
			));
			
			$this->update(array('receid' => $receid), $newid);
			
			return returnsuccess();
		}else{
			m('log')->addlogs('订阅运行', 'ID('.$id.')运行失败:'.$bstr.'', 2);
			return returnerror('error:'.$bstr.'');
		}
	}
}