<?php
/**
	上传文件类upfileChajian
	create：chenxihu
	createdt：2013-11-15
	explain：上传文件类，可上传任何文件类型
*/

class upfileChajian extends Chajian{
	
	public $ext;     //上传类型
	public $maxsize; //上传大小(MB)
	public $path;    //文件夹
	
	private $jpgallext		= '|jpg|png|gif|bmp|jpeg|';	//图片格式
	
	//可上传文件类型，也就是不保存为uptemp的文件
	private $upallfile		= '|doc|docx|xls|xlsx|ppt|pptx|pdf|swf|rar|zip|txt|gz|wav|mp3|avi|mp4|flv|wma|chm|apk|amr|log|json|cdr|psd|';
	
	/**
		初始化
		@param	$ext string 上传类型
		@param	$path string 上传目录 如：upload|e|ee
		@param	$maxsize ing 上传大小(MB)
	*/
	public function initupfile($ext,$path,$maxsize=1)
	{
		if($ext=='image')$ext = $this->jpgallext;
		$this->ext		= $ext;
		$this->maxsize	= $maxsize;
		$this->path		= $path;
	}
	
	private function _getmaxupsize($lx)
	{
		$iniMax = strtolower(ini_get($lx));
        if ('' === $iniMax) {
            return PHP_INT_MAX;
        }
        $max = ltrim($iniMax, '+');
        if (0 === strpos($max, '0x')) {
            $max = intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = intval($max, 8);
        } else {
            $max = (int) $max;
        }
        switch (substr($iniMax, -1)) {
            case 't': $max *= 1024;
            case 'g': $max *= 1024;
            case 'm': $max *= 1024;
            case 'k': $max *= 1024;
        }
        return $max;
	}
	
	public function getmaxupsize()
	{
		$post = $this->_getmaxupsize('post_max_size');
		$upmx = $this->_getmaxupsize('upload_max_filesize');
		if($post < $upmx)$upmx = $post;
		return $upmx;
	}
	
	public function getmaxzhao()
	{
		$size = $this->getmaxupsize();
		$size = $size / 1024 / 1024;
		return (int)$size;
	}
	
	/**
	*	是否在可保存范围内容
	*/
	public function issavefile($ext)
	{
		$bo 		= false;
		$upallfile	= $this->jpgallext.$this->upallfile;
		if($this->contain($upallfile, '|'.$ext.'|'))$bo = true;
		return $bo;
	}
	
	public function isimg($ext)
	{
		return $this->contain($this->jpgallext, '|'.$ext.'|');
	}
	
	/**
	*	判断是不是图片
	*/
	public function isimgsave($ext, $file)
	{
		$arr = array();
		if(!file_exists($file))return $arr;
		if($this->isimg($ext)){
			list($picw,$pich)	= getimagesize($file);
			if($picw==0||$pich==0){
				@unlink($file);
			}
			$arr[0] = $picw;
			$arr[1] = $pich;
		}
		return $arr;
	}
	
	public function isoffice($ext)
	{
		return contain('|doc|docx|xls|xlsx|ppt|pptx|pdf|', '|'.$ext.'|');
	}
	
	
	/**
		上传
		@param	$name	string	对应文本框名称
		@param	$cfile	string	文件名心的文件名，不带扩展名的
		@return	string/array
	*/
	public function up($name,$cfile='')
	{
		if(!$_FILES)return 'sorry!';
		$file_name		= $_FILES[$name]['name'];
		$file_size		= $_FILES[$name]['size'];//字节
		$file_type		= $_FILES[$name]['type'];
		$file_error		= $_FILES[$name]['error'];
		$file_tmp_name	= $_FILES[$name]['tmp_name'];
		$zongmax		= $this->getmaxupsize();	
		if($file_size<=0 || $file_size > $zongmax){
			return '文件为0字节/超过'.$this->formatsize($zongmax).'，不能上传';
		}
		$file_sizecn	= $this->formatsize($file_size);
		$file_ext		= $this->getext($file_name);//文件扩展名

		$file_img		= $this->isimg($file_ext);
		$file_kup		= $this->issavefile($file_ext);
		
		if(!$file_img && !$this->isoffice($file_ext) && getconfig('systype')=='demo')return '演示站点禁止文件上传';
		
		if($file_error>0){
			$rrs = $this->geterrmsg($file_error);
			return $rrs;
		}
			
		if(!$this->contain('|'.$this->ext.'|', '|'.$file_ext.'|') && $this->ext != '*'){
			return '禁止上传文件类型['.$file_ext.']';
		}
		
		if($file_size>$this->maxsize*1024*1024){
			return '上传文件过大，限制在：'.$this->formatsize($this->maxsize*1024*1024).'内，当前文件大小是：'.$file_sizecn.'';
		}
		
		//创建目录
		$zpath=explode('|',$this->path);
		$mkdir='';
		for($i=0;$i<count($zpath);$i++){
			$mkdir.=''.$zpath[$i].'/';
			if(!is_dir($mkdir))mkdir($mkdir);
		}
		
		//新的文件名
		$file_newname	= $file_name;
		$randname		= $file_name;
		if(!$cfile==''){
			$file_newname=''.$cfile.'.'.$file_ext.'';
		}else{
			$_oldval 	 = m('option')->getval('randfilename');
			$randname	 = $this->getrandfile(1, $_oldval);
			m('option')->setval('randfilename', $randname);
			$file_newname=''.$randname.'.'.$file_ext.'';
		}
		
		$save_path	= ''.str_replace('|','/',$this->path);
		//if(!is_writable($save_path))return '目录'.$save_path.'无法写入不能上传';
		$allfilename= $save_path.'/'.$file_newname.'';
		$uptempname	= $save_path.'/'.$randname.'.uptemp';

		$upbool	 	= true;
		if(!$file_kup){
			$allfilename= $this->filesave($file_tmp_name, $file_newname, $save_path, $file_ext);
			if(isempt($allfilename))return '无法保存到'.$save_path.'';
		}else{
			$upbool		= @move_uploaded_file($file_tmp_name,$allfilename);
		}
		
		if($upbool){
			$picw=0;$pich=0;
			if($file_img){
				$fobj = $this->isimgsave($file_ext, $allfilename);
				if(!$fobj){
					return 'error:非法图片文件';
				}else{
					$picw = $fobj[0];
					$pich = $fobj[1];	
				}
			}
			return array(
				'newfilename' => $file_newname,
				'oldfilename' => $file_name,
				'filesize'    => $file_size,
				'filesizecn'  => $file_sizecn,
				'filetype'    => $file_type,
				'filepath'    => $save_path,
				'fileext'     => $file_ext,
				'allfilename' => $allfilename,
				'picw'        => $picw,
				'pich'        => $pich
			);
		}else{
			return '上传失败：'.$this->geterrmsg($file_error).'';
		}
	}
	
	private function getrandfile($xu, $val)
	{
		$randname	= ''.date('d_His').''.rand(10,99*$xu).'';
		if($val==$randname)return $this->getrandfile($xu+1, $val);
		return $randname;
	}
	
	private function geterrmsg($code)
	{
		$arrs[1] = '上传文件大小超过服务器允许上传的最大值';
		$arrs[2] = '上传文件大小超过HTML表单中隐藏域MAX_FILE_SIZE选项指定的值';
		$arrs[6] = '没有找不到临时文件夹';
		$arrs[7] = '文件写入失败';
		$arrs[8] = 'php文件上传扩展没有打开';
		$arrs[3] = '文件只有部分被上传';
		$rrs 	 = '上传失败，可能是服务器内部出错，请重试';
		if(isset($arrs[$code]))$rrs=$arrs[$code];
		return $rrs;
	}
	
	//返回文件大小
	public function formatsize($size)
	{
		return $this->rock->formatsize($size);
	}
	
	//获取扩展名
	public function getext($file)
	{
		return strtolower(substr($file,strrpos($file,'.')+1));
	}
	
	/**
	*	非法文件保存为临时uptemp的形式
	*/
	public function filesave($oldfile, $filename, $savepath, $ext)
	{
		$file_kup	= $this->issavefile($ext);
		$ldisn 		= strrpos($filename, '.');
		if($ldisn>0)$filename = substr($filename, 0, $ldisn);
		$filepath 	= ''.$savepath.'/'.$filename.'.'.$ext.'';
		if(!$file_kup){
			$filebase64	= base64_encode(file_get_contents($oldfile));
			$filepath 	= ''.$savepath.'/'.$filename.'.uptemp';
			$bo 		= $this->rock->createtxt($filepath, $filebase64);
			@unlink($oldfile);
			if(!$bo)$filepath = '';
		}else{
		}
		return $filepath;
	}
}
