<?php
class wxgzh_oauthClassModel extends wxgzhModel
{
	
	public function initWxgzh()
	{
		$this->settable('wouser');
	}
	
	/**
	*	调整到获取
	*/
	public function oauthto($dlx='we')
	{
		$this->readwxset();
		if($this->appid==''){
			$this->returnerrmsg($dlx, '没有配置公众号');
			return false;
		}
		$state			= $this->rock->get('state','bang');
		$redurl			= ''.getconfig('outurl',URL).'?d='.$dlx.'&a=oauthback&m=login&state='.$state.'';
		$redirect_uri	= urlencode($redurl);
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';
		$this->rock->location($url);
		return true;
	}
	
	public function returnerrmsg($dlx='we',$errmsg, $ocan='')
	{
		$url = '?d='.$dlx.'&m=login&errmsg='.$this->rock->jm->base64encode($errmsg).''.$ocan.'';
		$this->rock->location($url);
		exit();
	}
	
	/**
	*	得到openid获取用户信息
	*/
	public function oauthback()
	{
		$code	= $this->rock->get('code');
		$state	= $this->rock->get('state');
		if($code=='')return '无法取得微信授权';
		
		$this->readwxset();
		$url 	= 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->secret.'&code='.$code.'&grant_type=authorization_code';
		$result = c('curl')->getcurl($url);
		$openid = '';
		$access_token = '';
		$errmsg 	= '无法获取微信用户openid';
		if($result != ''){
			$arr	= json_decode($result);
			if(isset($arr->openid))$openid = $arr->openid;
			if(isset($arr->access_token))$access_token = $arr->access_token;
			if(isset($arr->errmsg))$errmsg = $arr->errmsg;
		}
		
		if($openid != ''){
			$this->rock->savecookie('wxopenid', $openid);
			$nuarr 	= $this->getone("`openid`='$openid'");
			$uoid	= 0;
			//不要去重复拉起微信用户信息了
			if($nuarr){
				$uoid = (int)$nuarr['id'];
				$this->update(array(
					'optdt' => $this->rock->now,
					'uid'	=> $this->adminid
				), $uoid); //更新最后时间
				return $nuarr;
			}
			$gurl  	= 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
			$result = c('curl')->getcurl($gurl);//拉取用户信息
			if($result != ''){
				$arr	= json_decode($result, true);
				if(isset($arr['errcode']) && $arr['errcode']!=0){
					$errmsg	= $arr['errmsg'];
				}else{
					$where 				= 'id='.$uoid.'';
					$uarr['openid'] 	= $arr['openid'];
					$uarr['nickname'] 	= $arr['nickname'];
					$uarr['sex'] 		= $arr['sex'];
					$uarr['province'] 	= $arr['province'];
					$uarr['city'] 		= $arr['city'];
					$uarr['country'] 	= $arr['country'];
					$uarr['headimgurl'] = $arr['headimgurl'];
					$uarr['optdt'] 		= $this->rock->now;
					$uarr['ip'] 		= $this->rock->ip;
					$uarr['uid'] 		= $this->adminid;
					if($uoid==0){
						$uarr['adddt'] 	= $this->rock->now;
						$where			= '';
					}
					$bo 	= $this->record($uarr, $where);
					$errmsg	= '';//为空说明对了
					if(!$bo)$errmsg = $this->db->error();
				}
			}else{
				$errmsg	= '无法获取微信用户信息';
			}
		}
		if($errmsg==''){
			return $uarr;
		}else{
			return $errmsg;
		}
	}
	
	/**
	*	读取当前绑定微信用户信息
	*/
	public function getbdwx($uid)
	{
		$rs = $this->getone('`uid`='.$uid.'','`openid`,`nickname`,`headimgurl`');
		if(!$rs)$rs['nickname'] = '';
		return $rs;
	}
}