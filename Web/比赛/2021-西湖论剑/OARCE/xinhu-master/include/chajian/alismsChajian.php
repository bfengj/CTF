<?php
/**
*	阿里云短信服务api
*/
class alismsChajian extends Chajian{
	
	private $accesskeyid;
	private $accesskeysecret;
	
	protected function initChajian()
	{
		$this->accesskeyid 		= getconfig('alisms_keyid');
		$this->accesskeysecret 	= getconfig('alisms_keysecret');
	}
	
	/**
	*	批量发送短信
	*/
	public function send($mobiles, $qianm, $tplid, $cans=array())
	{
		if(isempt($this->accesskeyid) || isempt($this->accesskeysecret))return returnerror('没有设置短信keyid或keysecret');
		
		if(isempt($qianm))return returnerror('请设置短信签名');
		if(isempt($tplid) || substr($tplid,0,4) != 'SMS_')return returnerror('短信模版CODE格式有误');
		
		$mbarr = $this->getTplcont($tplid);
		if(!$mbarr['success'])return $mbarr;
		$tplcont = $mbarr['data']['TemplateContent'];
		
		if(isset($cans['url']))$cans['url'] = c('xinhuapi')->urlsmall($cans['url']); //如果有短域名用这个生成，不要就删掉这行
		
		//把没用参数删掉
		$csarr	= $this->rock->matcharr($tplcont);
		foreach($csarr as $cs1)if(!isset($cans[$cs1]))return returnerror('模版里有{'.$cs1.'}参数，发送必须传');
		foreach($cans as $k1=>$v1)if(!in_array($k1, $csarr))unset($cans[$k1]);
		
		
		$params = array();
		$shoujha= explode(',', $mobiles);
		$params["PhoneNumberJson"] 	= $shoujha;
		$params["TemplateCode"] 	=  $tplid;
		
		foreach($shoujha as $smid){
			$params["SignNameJson"][] 		= $qianm;
			if($cans)$params["TemplateParamJson"][] 	= $cans;
		}
	
		if($cans)$params["TemplateParamJson"]  = json_encode($params["TemplateParamJson"], JSON_UNESCAPED_UNICODE);
		$params["SignNameJson"] 		= json_encode($params["SignNameJson"], JSON_UNESCAPED_UNICODE);
		$params["PhoneNumberJson"] 	= json_encode($params["PhoneNumberJson"], JSON_UNESCAPED_UNICODE);
		
		$helper 	= new SignatureHelper();
		$result 	= $helper->request(
			$this->accesskeyid,
			$this->accesskeysecret,
			'dysmsapi.aliyuncs.com',
			array_merge($params, array(
				"RegionId" => "cn-hangzhou",
				"Action" => "SendBatchSms",
				"Version" => "2017-05-25",
			))
		);
		if(!$result)return returnerror('发送失败');
		$barr	= json_decode($result, true);
		if($barr['Code']=='OK')return returnsuccess($barr);
		return returnerror('发送失败:'.$result.'');
	}
	
	public function getTplcont($tplid)
	{
		$num = 'alisms_'.$tplid.'';
		$val = m('option')->getval($num);
		if(!isempt($val)){
			return returnsuccess(array('TemplateContent'=>$val));
		}
		if(isempt($this->accesskeyid) || isempt($this->accesskeysecret))return returnerror('没有设置短信keyid或keysecret');
		$helper 	= new SignatureHelper();
		$params['TemplateCode'] = $tplid;
		$result 	= $helper->request(
			$this->accesskeyid,
			$this->accesskeysecret,
			'dysmsapi.aliyuncs.com',
			array_merge($params, array(
				"RegionId" => "cn-hangzhou",
				"Action" => "QuerySmsTemplate",
				"Version" => "2017-05-25",
			))
		);
		if(!$result)return returnerror('获取模版失败'.$tplid.'');
		$barr	= json_decode($result, true);
		if($barr['Code']=='OK'){
			m('option')->setval($num, $barr['TemplateContent']);
			return returnsuccess($barr);
		}
		return returnerror('获取失败:'.$result.'');
	}
}

/**
 * 签名助手 2017/11/19
 *
 * Class SignatureHelper
 */
class SignatureHelper {

    /**
     * 生成签名并发起请求
     *
     * @param $accessKeyId string AccessKeyId (https://ak-console.aliyun.com/)
     * @param $accessKeySecret string AccessKeySecret
     * @param $domain string API接口所在域名
     * @param $params array API具体参数
     * @param $security boolean 使用https
     * @return bool|\stdClass 返回API接口调用结果，当发生错误时返回false
     */
    public function request($accessKeyId, $accessKeySecret, $domain, $params, $security=false) {
        $apiParams = array_merge(array (
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0,0xffff), true),
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "Format" => "JSON",
        ), $params);
        ksort($apiParams);

        $sortedQueryStringTmp = "";
        foreach ($apiParams as $key => $value) {
            $sortedQueryStringTmp .= "&" . $this->encode($key) . "=" . $this->encode($value);
        }

        $stringToSign = "GET&%2F&" . $this->encode(substr($sortedQueryStringTmp, 1));

        $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&",true));

        $signature = $this->encode($sign);

        $url = ($security ? 'https' : 'http')."://{$domain}/?Signature={$signature}{$sortedQueryStringTmp}";

        try {
            $content = $this->fetchContent($url);
            return $content;
        } catch( \Exception $e) {
            return false;
        }
    }

    private function encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }

    private function fetchContent($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "x-sdk-client" => "php/2.0.0"
        ));

        if(substr($url, 0,5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $rtn = curl_exec($ch);

        if($rtn === false) {
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);

        return $rtn;
    }
}