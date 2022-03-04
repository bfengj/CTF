<?php
class wxgzh_signjssdkClassModel extends wxgzhModel
{
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	
	public function getsignsdk($url) {
		$this->readwxset();
		$jsapiTicket = $this->getticket();
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
			"appId"     => $this->appid,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,

			"url"       => $url,
			"signature" => $signature,
			"jsapiTicket" => $jsapiTicket,
			"rawString" => $string
		);
		return $signPackage; 
	}
}