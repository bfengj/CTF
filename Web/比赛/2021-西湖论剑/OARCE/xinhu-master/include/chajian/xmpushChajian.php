<?php 
/**
*	小米推送
*	参考的开发：https://dev.mi.com/console/doc/detail?pId=1163
*/
class xmpushChajian extends Chajian{
	
	private $api_url 	 = 'https://api.xmpush.xiaomi.com/v2/message/alias';
	private $api_urltest = 'https://sandbox.xmpush.xiaomi.com/v2/message/alias';
	
	
	//安卓的
	private $android_secret 	= ''; //安卓小米的secret
	private $android_package 	= ''; //包名
	
	//IOS苹果(弃用不要去设置修改)
	private $ios_secret 		= ''; //IOS的secret
	private $ios_bundleid 		= '';

	//2021-10-09最新IOS推送使用极光的，如果自己编译IOS就要配置
	private $jpush_app_key 			= '';
	private $jpush_master_secret 	= '';
	private $jpush_ios_ver 			= ''; //IOS编译的版本1正式版
	
	
	protected function initChajian()
	{
		if(!$this->android_secret)$this->android_secret 	= getconfig('xm_android_secret');
		if(!$this->android_package)$this->android_package 	= getconfig('xm_android_package');
		$this->jpush_app_key 		= getconfig('jpush_app_key');
		$this->jpush_master_secret 	= getconfig('jpush_master_secret');
		$this->jpush_ios_ver 		= getconfig('jpush_ios_ver');
	}
	
	public function sendbool()
	{
		if($this->android_secret=='')return false;
		return true;
	}

	/**
	*	安卓推送通知
	*/
	public function androidsend($alias, $title, $cont, $payload='')
	{
		if(!$alias || !$this->sendbool())return '';
		if(is_array($alias))$alias = join(',', $alias);
		$data = array(
			'payload' => '',
			'restricted_package_name' => $this->android_package,
			'pass_through' => '0',   // 0 表示通知栏消息1表示透传消息
			'title' => $title,
			'description' => $cont,
			'alias' => $alias,
			'notify_type' => '1', //提示语就好了
			'notify_id' => rand(1,45), //可多条显示
			'extra.notify_foreground' => '1',
			'extra.notify_effect' => '1',
		);
		if($payload)$data['payload'] = urlencode($payload);
		return c('curl')->postcurl($this->api_url, $data, 0, array(
			'Authorization'=> 'key='.$this->android_secret
		));
	}
	
	//弃用(2021-10-09)
	public function iossend($alias, $title, $cont)
	{
		if(!$alias || $this->ios_secret=='')return '';
		if(is_array($alias))$alias = join(',', $alias);
		$data = array(
			'title' => $title,
			'aps_proper_fields.title' => $title,
			'description' => $cont,
			'aps_proper_fields.body' => $cont,
			'alias' => $alias,
			'extra.badge'=>'1'
		);
		return c('curl')->postcurl($this->api_url, $data, 0, array(
			'Authorization'=> 'key='.$this->ios_secret
		));
	}
	
	
	/**
	*	推送以下，你用不到不要去管和修改
	*/
	public function jpushiosbool()
	{
		if($this->jpush_app_key=='')return false;
		return true;
	}
	public function jpushiossend($alias, $title, $cont, $iszs=false)
	{
		if(!$this->jpushiosbool())return '';
		if($this->jpush_ios_ver==1)$iszs = true;
		$url = 'https://api.jpush.cn/v3/push';
		$body = array();
		$base64_auth_string = base64_encode(''.$this->jpush_app_key.':'.$this->jpush_master_secret.'');
		$headarr['Authorization'] = 'Basic '.$base64_auth_string.'';
		if(is_string($alias))$alias = explode(',', $alias);
		
		$body['platform'] = array('ios');
		$body['audience'] = array(
			'alias' => $alias
		);
		
		$sound = 'widget/res/sound/sound.aif';
		if($cont=='邀请与您视频通话...' || $cont=='邀请与您语音通话...')$sound='widget/res/sound/call.mp3';
		
		$body['notification'] = array(
			'ios' => array(
				'alert'	=> array(
					'title' => $title,
					'body' => $cont,
				),
				'sound' => $sound,
				'badge' => '+1',
				'thread-id' => 'default',
			)
		);
		$body['options'] = array(
			'time_to_live' => 60,
			'apns_production' => $iszs, //false测试环境
 		);
		
		$result = c('curl')->postcurl($url, json_encode($body, JSON_UNESCAPED_UNICODE), 0, $headarr);
		
		return $result;
	}
}