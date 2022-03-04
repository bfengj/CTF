<?php 
/**
*	imap 收邮件扩展

	imap_search 使用返回Id
	http://php.net/manual/en/function.imap-search.php
	ALL	返回所有合乎标准的信件
	ANSWERED	信件有配置 \\ANSWERED 标志者
	BCC "字符串"	Bcc 栏中有指定 "字符串" 的信件
	BEFORE "日期"	指定 "日期" 以前的信件
	BODY "字符串"	内文字段中有指定 "字符串" 的信件
	CC "字符串"	Cc 栏中有指定 "字符串" 的信件
	DELETED	合乎已删除的信件
	FLAGGED	信件有配置 \\FLAGGED 标志者
	FROM "字符串"	From 栏中有指定 "字符串" 的信件
	KEYWORD "字符串"	关键字为指定 "字符串" 者
	NEW	新的信件
	OLD	旧的信件
	ON "日期"	指定 "日期" 的信件
	RECENT	信件有配置 \\RECENT 标志者
	SEEN	信件有配置 \\SEEN 标志者
	SINCE "日期"	指定 "日期" 之后的信件
	SUBJECT "字符串"	Subject 栏中有指定 "字符串" 的信件
	TEXT "字符串"	Text 栏中有指定 "字符串" 的信件
	TO "字符串"	To 栏中有指定 "字符串" 的信件
	UNANSWERED	未回应的信件
	UNDELETED	未删除的信件
	UNFLAGGED	未配置标志的信件
	UNKEYWORD "字符串"	未配置关键 "字符串" 的信件
	UNSEEN	未读取的信件
*/

class imapChajian extends Chajian
{
	private $supportbool = true;
	
	protected function initChajian()
	{
		$this->supportbool 	= $this->isimap();
		$this->marubox		= false;
	}
	
	public function isimap()
	{
		return function_exists('imap_open');
	}
	
	
	/**
	*	读取某日期后的邮件
	*	@params $link 服务器
	*	@params $user 邮箱
	*	@params $pass 邮箱密码
	*	@params $time 时间戳，默认7天前的
	*/
	public function receemail($link, $user, $pass, $time=0)
	{
		if(isempt($link))return '未设置收邮件imap服务器';
		if(isempt($user))return '用户未设置邮箱';
		if(isempt($pass))return '邮箱['.$user.']未设置密码';
		
		if(!$this->supportbool)return '系统未开启imap收邮件扩展';
		$this->marubox 			= @imap_open($link,$user,$pass);
		$this->struck_tearr		= array();
		if(!$this->marubox)return '不能连接到['.$link.']可能帐号密码有错';
		if($time == 0)$time		= time() - 7*24*3600;
		$ondt 					= date('j M Y', $time);
		$searcharr 				= imap_search($this->marubox, 'SINCE "'.$ondt.'"');//指定日期之后
		$rows 					= array();
		//return $searcharr;
		if($searcharr)foreach($searcharr as $k=>$i){
			$headers 	= $this->getheader($i);
			$body 		= $this->getBody($i);
			$headers['body']	= $body;
			$headers['num']		= $i;
			$headers['attach']	= $this->getattach($i);
			$rows[] = $headers;
		}
		imap_close($this->marubox, CL_EXPUNGE);
		return $rows;
		$totalrows 	= imap_num_msg($this->marubox); //取得信件数
		$rows 		= array();
		for ($i=1;$i<=$totalrows;$i++){
			$headers 	= $this->getheader($i); //获取某信件的标头信息
			$body 		= $this->getBody($i); 	//获取信件正文
			$headers['body']	= $body;
			$headers['num']		= $i;
			$headers['attach']	= $this->getattach($i);
			$rows[] = $headers;
		}
		imap_close($this->marubox, CL_EXPUNGE);
		return $rows;
	}
	
	/**
	*	下载附件
	*/
	public function downattach($link, $user, $pass, $num, $key)
	{
		
	}
	
	private function getfetchstructure($i)
	{
		if(!isset($this->struck_tearr[$i])){
			$struck = imap_fetchstructure($this->marubox,$i);
		}else{
			$struck = $this->struck_tearr[$i];
		}
		return $struck;
	}
	
	/**
	*	获取附件
	*/
	private function getattach($i)
	{
		$arr 	= array();
		$struck = $this->getfetchstructure($i);
		if($struck && isset($struck->parts))foreach($struck->parts as $key=>$val){
			if($val->subtype=='OCTET-STREAM'){
				if(isset($val->dparameters[0]))$arr[] = array(
					'filename' => $this->_imap_utf8($val->dparameters[0]->value),
					'filesize' => $val->bytes,
					'encoding' => $val->encoding,
					'filekey'  => $key,
					'attachcont' => $this->getattachcont($i, $key, $val->encoding) //获取附件内容
				);
			}
		}
		return $arr;
	}
	
	/**
	*	附件内容读取，需要额外读取
	*/
	public function getattachcont($i, $key, $encoding)
	{
		$message = imap_fetchbody($this->marubox, $i, $key + 1);
		switch ($encoding) {
			case 0:
				$message = imap_8bit($message);
				break;
			case 1:
				$message = imap_8bit($message);
				break;
			case 2:
				$message = imap_binary($message);
				break;
			case 3:
				$message = imap_base64($message);
				break;
			case 4:
				$message = quoted_printable_decode($message);
				break;
			case 5:
				$message = $message;
				break;
		}
		return $message;
	}
	
	private function getkevel($st, $kdy, $dev='')
	{
		return objvalue($st, $kdy, $dev);
	}
	
	/**
	*	获取某信件的标头信息
	*/
	private function getheader($i)
	{
		$headers 	= imap_header($this->marubox, $i);

		$arr['subject'] 	= $this->_imap_utf8($this->getkevel($headers,'subject'));//标题
		$arr['message_id'] 	= $this->getkevel($headers,'message_id');//邮件ID
		$arr['size'] 		= $this->getkevel($headers,'Size','0');	//邮件大小
		$arr['date'] 		= date('Y-m-d H:i:s', strtotime($this->getkevel($headers,'date')));
		$arr['to']			= array();
		$arr['from']		= array();
		
		//发给
		if(isset($headers->to)){
			$arr['to']			= $headers->to;
			foreach($arr['to'] as $k=>$rs){
				$arr['to'][$k]->personal = $this->_imap_utf8($this->getkevel($rs, 'personal'));
				$arr['to'][$k]->email 	 = ''.$rs->mailbox.'@'.$rs->host.'';
			}
		}
		$arr['toemail']		= $this->stremail($arr['to']);
		
		//发件人
		if(isset($headers->from)){
			$arr['from']			= $headers->from;
			foreach($arr['from'] as $k=>$rs){
				$arr['from'][$k]->personal 	= $this->_imap_utf8($this->getkevel($rs, 'personal'));
				$arr['from'][$k]->email 	= ''.$rs->mailbox.'@'.$rs->host.'';
			}
		}
		$arr['fromemail']		= $this->stremail($arr['from']);
		
		//回复的邮件
		if(isset($headers->reply_to)){
			$arr['reply_to']			= $headers->reply_to;
			foreach($arr['reply_to'] as $k=>$rs){
				$arr['reply_to'][$k]->personal 	= $this->_imap_utf8($this->getkevel($rs, 'personal'));
				$arr['reply_to'][$k]->email 	= ''.$rs->mailbox.'@'.$rs->host.'';
			}
			$arr['reply_toemail']		= $this->stremail($arr['reply_to']);
		}else{
			$arr['reply_toemail']		= $arr['fromemail'];
		}
		
		//抄送
		$arr['cc']				= array();
		if(isset($headers->cc)){
			$arr['cc']			= $headers->cc;
			foreach($arr['cc'] as $k=>$rs){
				$arr['cc'][$k]->personal = $this->_imap_utf8($this->getkevel($rs, 'personal'));
				$arr['cc'][$k]->email 	 = ''.$rs->mailbox.'@'.$rs->host.'';
			}
		}
		$arr['ccemail']			= $this->stremail($arr['cc']);
		
		$arr['headers'] 		= $headers;
		return $arr;
	}
	
	private function stremail($arr)
	{
		$str = '';
		if(is_array($arr))foreach($arr as $k=>$rs){
			$str.=','.$rs->personal.'('.$rs->mailbox.'@'.$rs->host.')';
		}
		if($str!='')$str = substr($str, 1);
		return $str;
	}
	
	
	private function _imap_utf8($text) {  
		$text	=  iconv_mime_decode($text,0, 'UTF-8'); 
        return $text;  
    }  
  
    private function _iconv_utf8($text) {
		$encode = mb_detect_encoding($text, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		if($encode != 'UTF-8'){
			return @iconv($encode, 'utf-8', $text);
		}else{
			return $text;
		}
        @$s1 = iconv('gbk', 'utf-8', $text);  
        $s0  = iconv('utf-8', 'gbk', $s1);  
        if ($s0 == $text) {  
            return $s1;  
        } else {  
            return $text;  
        }  
    }

	function get_mime_type(&$structure) { //Get Mime type Internal Private Use  
        $primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");  
  
        if ($structure->subtype) {  
            return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype;  
        }  
        return "TEXT/PLAIN";  
    } 
	
	private function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false) { //Get Part Of Message Internal Private Use  
        if (!$structure) {  
            $structure = $this->getfetchstructure($msg_number);; 
        }  
        if ($structure) {  
            if ($mime_type == $this->get_mime_type($structure)) {  
                if (!$part_number) {  
                    $part_number = "1";  
                }  
                $text = imap_fetchbody($stream, $msg_number, $part_number); 
                if ($structure->encoding == 3) {  
                    return imap_base64($text);  
                } else if ($structure->encoding == 4) {  
                    return imap_qprint($text);  
                } else {  
                    return $text;  
                }  
            }  
            if ($structure->type == 1) /* multipart */ {  
                while (list($index, $sub_structure) = each($structure->parts)) {  
                    $prefix = '';  
                    if ($part_number) {  
                        $prefix = $part_number . '.';  
                    }  
                    $data = $this->get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));  
                    if ($data) {  
                        return $data;  
                    }  
                }  
            }  
        }  
        return false;  
    } 
	
	/**
	*	获取邮件内容
	*/
	private function getBody($mid) { 
        $body = $this->get_part($this->marubox, $mid, "TEXT/HTML");  
        if ($body == "") {  
            $body = $this->get_part($this->marubox, $mid, "TEXT/PLAIN");  
        }  
        if ($body == "") {  
            return "";  
        }  
        return $this->_iconv_utf8($body);  
    }
}