<?php 
/**
	PHPMailer 读取插件类
*/

include_once(ROOT_PATH.'/include/PHPMailer/class.phpmailer.php');
include_once(ROOT_PATH.'/include/PHPMailer/class.smtp.php');
class mailerChajian extends Chajian{
	
	private $mail;
	private $mailbool;
	
	public function initChajian(){
		$this->mail     = new PHPMailer();
		$this->mail->IsSMTP();
		$this->mail->SMTPAuth   = true;
		$this->mail->SMTPDebug  = 0;    
		$this->mail->CharSet	= 'UTF-8';  
		$this->mailbool  		= false;  
	}
	
	public function setHost($host, $port=25, $secure=''){
		$this->mail->Host       = $host;
		$this->mail->SMTPSecure = $secure;
		$this->mail->Port       = (int)$port;
	}
	
	public function setUser($email, $pass){
		$this->mail->Username   = $email;
		$this->mail->Password   = $pass;   
		$this->setFrom($email);
		$this->addReplyTo($email);
	}
	
	//发件人邮箱地址  
	public function setFrom($from, $name=''){
		$this->mail->SetFrom($from, $this->tojoin($name));
	}
	
	//设置回复
	public function addReplyTo($address, $name=''){
		$this->mail->AddReplyTo($address, $this->tojoin($name));
	}
	
	//添加抄送
	public function addCC($address, $name=''){
		$a1	= explode(',', $address);
		$n1 = array();
		if($name != '')$n1 = explode(',', $name);
		for($i=0; $i<count($a1); $i++){
			$na = '';
			if(isset($n1[$i]))$na = $n1[$i];
			$this->mail->AddCC($a1[$i], $this->tojoin($na));
		}
	}
	
	//添加秘密送
	public function addBCC($address, $name=''){
		$a1	= explode(',', $address);
		$n1 = array();
		if($name != '')$n1 = explode(',', $name);
		for($i=0; $i<count($a1); $i++){
			$na = '';
			if(isset($n1[$i]))$na = $n1[$i];
			$this->mail->AddBCC($a1[$i], $this->tojoin($na));
		}
	}
	
	//添加收件人
	public function addAddress($address, $name=''){
		$a1	= explode(',', $address);
		$n1 = array();
		if($name != '')$n1 = explode(',', $name);
		for($i=0; $i<count($a1); $i++){
			$na = '';
			if(isset($n1[$i]))$na = $n1[$i];
			$this->mail->AddAddress($a1[$i], $this->tojoin($na));
		}
	}
	
	//添加附件
	public function addAttachment($address, $name=''){
		if($this->rock->isempt($address))return;
		$a1	= explode(',', $address);
		$n1 = array();
		if($name != '')$n1 = explode(',', $name);
		for($i=0; $i<count($a1); $i++){
			$na = '';
			if(isset($n1[$i]))$na = $n1[$i];
			$this->mail->AddAttachment(ROOT_PATH.'/'.$a1[$i], $this->tojoin($na)); 
		}
	}
	
	//发送邮件
	public function sendMail($Subject, $body=''){
		$this->mail->Subject = $this->tojoin($Subject);
		$this->mail->Body 	 = $body;
		$this->mail->IsHTML(true);
		$this->mailbool		 = $this->mail->Send();
	}
	
	public function isSuccess(){
		return $this->mailbool;
	}
	
	public function getErrror(){
		return $this->mail->ErrorInfo;
	}
	
	private function tojoin($name='')
	{
		if($name=='')return '';
		return "=?UTF-8?B?".base64_encode($name)."?=";
	}
}	       