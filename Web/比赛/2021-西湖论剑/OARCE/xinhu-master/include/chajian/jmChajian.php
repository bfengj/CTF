<?php 
/**
	加密解密插件
*/
class jmChajian extends Chajian{
	
	private $keystr = 'abcdefghijklmnopqrstuvwxyz';
	private $jmsstr = '';
	public  $rocktokenarr = array();

	protected function initChajian()
	{
		$this->initJm();
	}
	
	public function initJm()
	{
		$this->jmsstr = getconfig('randkey');
		$this->setRandkey($this->jmsstr);
		$this->getkeyshow();
	}
	
	public function setRandkey($str)
	{
		$this->jmsstr = $str;
		if(strlen($this->jmsstr)!=26)$this->jmsstr = $this->keystr;
		$this->getrocktoken();
	}
	
	public function getRandkey()
	{
		$str = $this->keystr;
		$s 	 = '';$len = strlen($str);
		$j 	 = $len-1;
		for($i=0; $i<$len; $i++){
			$r = rand(0, $j);
			$zm= substr($str, $r, 1);
			$s.= $zm;
			$str = str_replace($zm,'',$str);
			$j--;
		}
		return $s;
	}
	
	public function getint($str)
	{
		$len = strlen($str);
		$oi  = 0;
		for($i=0; $i<$len; $i++){
			$l = substr($str,$i,1);
			$j = ord($l)-90;
			$oi+=$j;
		}
		if($oi<0)$oi=0-$oi;
		return $oi;
	}
	
	private function getrandstr($oi, $str='')
	{
		if($str=='')$str=$this->keystr;
		if($oi>100)$oi=100;
		$len = strlen($str);
		$qs  = 6;
		$s1  = substr($str, 0, $qs);
		$s2	 = substr($str, $qs, $qs);
		$s3  = substr($str, $qs*2, $len-$qs*2);
		$s   = $s3.$s2.$s1;
		if($oi>0)$s=$this->getrandstr($oi-1, $s);
		return $s;
	}
	
	public function getkeyshow()
	{
		$str = '~!@#$%^&*()_+{}[];"<>?:-=.';
		$len = strlen($this->jmsstr);
		$s 	 = '';
		for($i=0;$i<$len;$i++){
			$l = substr($this->jmsstr,$i,1);
			$j = ord($l)-97;
			$s.= substr($str,$j,1);
		}
		return $s;
	}

	public function base64encode($str)
	{
		if(isempt($str))return '';
		$str	= base64_encode($str);
		$str	= str_replace(array('+', '/', '='), array('!', '.', ':'), $str);
		return $str;
	}
	
	public function base64decode($str)
	{
		if(isempt($str))return '';
		$str	= str_replace(array('!', '.', ':'), array('+', '/', '='), $str);
		$str	= base64_decode($str);
		return $str;
	}
	
	private function _getss($lx)
	{
		$st = '';
		if(is_numeric($lx)&&$lx>0){
			$st = $this->getrandstr($lx);
		}else if(is_string($lx)){
			if(strlen($lx)==26)$st=$lx;
		}
		return $st;
	}
	
	public function encrypt($str, $lx='')
	{
		$st = $this->_getss($lx);
		$s	= $this->base64encode($str);
		$s	= $this->encrypts($s, $st);
		return $s;
	}

	public function uncrypt($str, $lx='')
	{
		$st = $this->_getss($lx);
		$s	= $this->uncrypts($str, $st);
		$s	= $this->base64decode($s);
		return $s;
	}
	
	public function encrypts($str, $a='')
	{
		if($a=='')$a = $this->jmsstr;
		$nstr	= '';
		if($this->rock->isempt($str)) return $nstr;
		$len	= strlen($str);
		$t		= rand(1, 14);
		if($t == 10)$t++;
		for($i=0; $i<$len; $i++){
			$nstr.='0';
			$sta	= substr($str,$i,1);
			$orstr	= ''.ord($sta).'';
			$ile	= strlen($orstr);
			for($j=0; $j<$ile; $j++){
				$oi	= (int)substr($orstr,$j,1)+$t;
				$nstr.= substr($a,$oi,1);
			}
		}
		if($nstr != ''){
			$nstr = substr($nstr,1);
			$nstr.= '0'.$t.'';
		}	
		return $nstr;
	}
	
	public function uncrypts($str, $a1='')
	{
		$nstr	= '';
		if($this->rock->isempt($str)) return $nstr;
		if($a1=='')$a1	= $this->jmsstr;
		$a	= array();
		for($i=0; $i<strlen($a1); $i++)$a[substr($a1, $i, 1)] = ''.$i.'';
		$na	= explode('0', $str);
		$len= count($na);
		$r	= (int)$na[$len-1];
		for($i=0; $i<$len-1; $i++){
			$st	= $na[$i];
			$sl = strlen($st);
			$sa	= '';
			for($j=0; $j<$sl; $j++){
				$ha	= substr($st,$j,1);
				if(isset($a[$ha]))$ha = $a[$ha] - $r;
				$sa.=$ha;
			}
			$sa	= (int)$sa;
			$nstr.=chr($sa);
		}
		return $nstr;
	}
	
	
	public function getrocktoken()
	{
		$toke 	= $this->rock->get('rocktoken');
		$str 	= $this->uncrypt($toke);
		if($toke!='' && !$this->contain($str,'&'))exit('sorry,not found!');
		$arr	= array('m'=>'index','a'=>'default','d'=>'');
		if($str){
			$a = explode('&', $str);
			foreach($a as $a1){
				$a2 = explode('=', $a1);
				$arr[$a2[0]] = $a2[1];
			}
		}
		$this->rocktokenarr = $arr;
		return $arr;
	}
	
	public function gettoken($na, $dev='')
	{
		$s = $dev;
		if(isset($this->rocktokenarr[$na])){
			$s = $this->rocktokenarr[$na];
		}else{
			$s = $this->rock->get($na, $dev);
		}			
		return $s;
	}
	
	public function strrocktoken($a=array())
	{
		$s = '';
		foreach($a as $k=>$v){
			$s .='&'.$k.'='.$v.'';
		}
		if($s!=''){
			$s = $this->encrypt(substr($s, 1));
		}
		return $s;
	}
	
	public function mcrypt_encrypt($str)
	{
		if(isempt($str))return '';
		if(!function_exists('mcrypt_encrypt'))return $str;
		$key		= substr(md5($this->jmsstr),0,8);
		$getstr 	= mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
		return $this->base64encode($getstr);
	}
	
	public function mcrypt_decrypt($str)
	{
		if(isempt($str))return '';
		if(!function_exists('mcrypt_decrypt'))return $str;
		$str 		= $this->base64decode($str);
		$key		= substr(md5($this->jmsstr),0,8);
		$getstr 	= mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
		return trim($getstr);
	}
	
	/**
	*	字符串加密处理
	*/
	public function strlook($data,$key='')
	{
		if(isempt($data))return '';
		if($key=='')$key	= md5($this->jmsstr);
		$x		= 0;
		$len	= strlen($data);
		$l		= strlen($key);
		$char 	= $str = '';
		for ($i = 0; $i < $len; $i++){
			if ($x == $l) {
				$x = 0;
			}
			$char .= $key[$x];
			$x++;
		}
		for ($i = 0; $i < $len; $i++){
			$str .= chr(ord($data[$i]) + (ord($char[$i])) % 256);
		}
		return $this->base64encode($str);
	}
	
	/**
	*	字符串解密
	*/
	public function strunlook($data,$key='')
	{
		if(isempt($data))return '';
		if($key=='')$key	= md5($this->jmsstr);
		$x 		= 0;
		$data 	= $this->base64decode($data);
		$len 	= strlen($data);
		$l 		= strlen($key);
		$char 	= $str = '';
		for ($i = 0; $i < $len; $i++){
			if ($x == $l) {
				$x = 0;
			}
			$char .= substr($key, $x, 1);
			$x++;
		}
		for ($i = 0; $i < $len; $i++){
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))){
				$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
			}else{
				$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
		}
		return $str;
	}
}