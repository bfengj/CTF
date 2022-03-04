<?php 
/**
*	腾讯云上文件存储上传管理
*/
include_once(ROOT_PATH.'/include/cos-php-sdk-v4-master/include.php');
use Qcloud\Cos\Api;

class qcloudCosChajian extends Chajian{
	
	private $bucket	= 'xinhuoa';
	private $folder	= 'defile'; //默认目录
	private $region	= 'sh'; //默认分区
	
	
	protected function initChajian()
	{
		$this->bucket = getconfig('qcloudCos_bucket', $this->bucket);
		$this->region = getconfig('qcloudCos_region', $this->region);
		$this->folder = getconfig('qcloudCos_folder', $this->folder);
		$this->app_id = getconfig('qcloudCos_APPID');
		
		$config = array(
			'app_id' 	=> $this->app_id,
			'secret_id' => getconfig('qcloudCos_SecretId'),
			'secret_key'=> getconfig('qcloudCos_SecretKey'),
			'region' 	=> $this->region,
			'timeout' 	=> 180
		);

		$this->cosApi = new Api($config);
		
	}
	
	/**
	*	上传文件
	*	filepath 要上传的文件全路径
	*	updir 上传到哪个目录
	*	upname 上传后保存文件名
	*/
	public function upload($filepath, $updir='', $upname='')
	{
		if(!file_exists($filepath))return false;
		$filea 	= explode('/', $filepath);
		if($upname=='')$upname = $filea[count($filea)-1];
		if($updir=='')$updir = $this->folder;
		$ret = $this->cosApi->upload($this->bucket, $filepath, ''.$updir.'/'.$upname.'');
		if($ret['code']==0)$ret['url'] = $this->geturl().'/'.$updir.'/'.$upname.'';
		return $ret;
	}
	
	/**
	*	创建文件夹
	*/
	public function createFolder($folder)
	{
		$ret = Cosapi::createFolder($this->bucket, $folder);
		return $ret;
	}
	
	/**
	*	获取目录下的文件
	*/
	public function listFolder($folder='', $num=20)
	{
		if($folder=='')$folder = $this->folder;
		$ret = $this->cosApi->listFolder($this->bucket, $folder, $num);
		if($ret['code'] != 0){
			return returnerror($ret['message']);
		}else{
			$barr = returnsuccess($ret['data']['infos']);
			$barr['folder'] = $folder;
			return $barr;
		}
	}
	
	/**
	*	删除文件
	*/
	public function delFile($path)
	{
		$ret = $this->cosApi->delFile($this->bucket, $path);
		return $ret;
	}
	
	public function delListFile()
	{
		$barr = $this->listFolder('',100);
		if($barr['success']){
			foreach($barr['data'] as $k=>$rs){
				$this->delFile($this->folder.'/'.$rs['name']);
			}
		}
	}
	
	/**
	*	下载文件到服务器本地
	*/
	public function download($srcPath, $dstPath)
	{
		$res = $this->cosApi->download($this->bucket, $srcPath, $dstPath);
		return $res;
	}
	
	/**
	*	获取外网访问地址
	*/
	public function geturl()
	{
		$xarr['nj'] = 'ap-nanjing';
		$xarr['cd'] = 'ap-chengdu';
		$xarr['bj'] = 'ap-beijing';
		$xarr['gz'] = 'ap-guangzhou';
		$xarr['sh'] = 'ap-shanghai';
		$xarr['cq'] = 'ap-chongqing';
		$xarr['bjfsi'] = 'ap-beijing-fsi';
		$xarr['szfsi'] = 'ap-shenzhen-fsi';
		$xarr['shfsi'] = 'ap-shanghai-fsi';
		$xarr['hk']    = 'ap-hongkong';
		$qustr = arrvalue($xarr, $this->region,'ap-shanghai');
		$url = 'https://'.$this->bucket.'-'.$this->app_id.'.cos.'.$qustr.'.myqcloud.com';
		return $url;
	}
}