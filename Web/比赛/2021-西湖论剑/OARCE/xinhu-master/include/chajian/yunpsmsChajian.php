<?php
/**
*	来自：信呼开发团队
*	作者：磐石(rainrock)
*	网址：http://www.rockoa.com/
*	云片网短信服务
*	此文件放到：include/chajian/yunpsmsChajian.php 下
*	开发帮助地址：http://www.rockoa.com/view_yunpsms.html
*/
class yunpsmsChajian extends Chajian{
	
	private $sendurl 		= 'https://sms.yunpian.com/v2/sms/batch_send.json';
	
	private $yunpian_key 	= '你的短信apikey'; //这里填写你的云片网的apikey
	
	/**
	*	短信模版写这里的
	*/
	protected function initChajian()
	{
		
		$mobian['defyzm'] 	= '欢迎使用，您的短信验证码为：#code#，为保障系统安全，请勿将验证码和办公系统网址提供给他人，祝您工作愉快。';
		$mobian['defsucc'] 	= '您提交单据(#modename#,单号:#sericnum#)已全部处理完成，可登录系统查看详情。';
		$mobian['default'] 	= '您有单据(#modename#,单号:#sericnum#)需要处理，请登录系统及时去处理。';
		$mobian['birthday'] = '尊敬的#name#，今天是#dt#，农历#dtnong#，是您的生日，我们在这里祝您生日快乐。';
		$mobian['defnum'] 	= '您有#applyname#的(#modename#)单据需要您处理，详情：#url#';
		$mobian['defurls'] 	= '您有单据(#modename#,单号:#sericnum#)需要处理，请及时去处理，详情：#url#。';
		$mobian['gongsms'] 		= '您收到一条“#title#”的通知，详情：#url#';
		$mobian['meetapply'] 	= '#optname#发起会议“#title#”在#hyname#，时间#startdt#至#enddt#';
		$mobian['meetcancel'] 	= '#optname#取消会议“#title#”，时间#startdt#至#enddt#，请悉知。';
		$mobian['meettodo'] 	= '会议“#title#”将在#fenz#分钟后的#time#开始请做好准备，在会议室“#hyname#”';
		
		//上面加你的模版
		

		$this->mobianarr 	= $mobian;
	}
	
	/**
	*	批量发送短信
	*	$mobiles 接收人手机号多个,分开
	*	$qianm 签名
	*	$tplid 模版编号，在上面initChajian()数组中查找
	*	$cans 模版中的参数数组
	*	例子：c('mysms')->send('15800000000,15800000001','信呼', 'default', array('modename'=>'模块名','sericnum'=>'单号')); 这例子是不需要自己调用，只要短信设置下切换为“我的短信服务”就可以了
	*/
	public function send($mobiles, $qianm, $tplid, $cans=array())
	{
		//要发送短信的内容
		$text	= arrvalue($this->mobianarr, $tplid);
		if(isempt($text))return returnerror('模版'.$tplid.'不存在');
		foreach($cans as $k=>$v)$text = str_replace('#'.$k.'#', $v, $text);
		if(isempt($qianm))return returnerror('没有设置短信签名');
		if(isempt($this->yunpian_key))return returnerror('没有设置云片网的apikey');

		
		$text = '【'.$qianm.'】'.$text.''; //发的短信

		$arr['apikey'] 	= $this->yunpian_key;
		$arr['mobile'] 	= $mobiles;
		$arr['text'] 	= $text;
		$result 		= c('curl')->postcurl($this->sendurl, $arr);
		
		if(isempt($result))return returnerror('发送失败');

		$barr 			= json_decode($result, true);
		$data 			= arrvalue($barr,'data');
		$total_count	= 0; //总发送条数
		$sendbo 		= false;
		$msg 			= '';

		if(is_array($data))foreach($data as $k=>$rs){
			if($rs['code']==0){
				$sendbo	= true;
			}else{
				$msg 	.= $rs['msg'].';';
			}
		}
		
		//成功
		if($sendbo){
			return returnsuccess(array(
				'count'	=> $barr['total_count'],
				'text'	=> $text,
				'mobile'=> $mobiles,
			));
		}else{
			return returnerror('发送失败:'.$msg.'('.$text.')', 202);
		}

	}
}