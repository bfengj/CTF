<?php
/**
	gd2库图片处理，添加水印
	
*/
class imageChajian extends Chajian
{
	public $ext; 	//图片类型
	public $img; 	//图片对象
	public $mime; 	//图片对象
	public $path; 	//图片地址
	public $w 		= 0;
	public $h 		= 0;
	public $bool	= false;
	public $size 	= 0;
	public $whbili	= 0;//高和宽的比例
	
	
	protected function initChajian()
	{
		$this->bool	= function_exists('ImageCreate');
	}
	
	/**
		创建图片对象$rotate旋转角度
		
	*/
	public function createimg($path,$rotate=0)
	{
		if(!$this->bool)return false;
		$this->bool = false;
		if(!file_exists($path))return false;
		
		$this->ext	= $this->getext($path);
		$this->path	= $path;
		$img		= $this->createimgobj($path);
		$this->img	= $img;
		if(!$img)return false;
		//判断是否旋转
		if($rotate != 0 && $rotate<360 && $rotate>-360){
			$white	= $this->color('#ffffff',$img);
			$img	= imagerotate($img, $rotate, $white);//旋转
		}
		
		
		$this->w	= imagesx($this->img);
		$this->h	= imagesy($this->img);
		$this->size	= ceil(filesize($this->path)/1024);
		$this->whbili	= $this->w/$this->h;
		$this->bool	= true;
	}
	
	
	
	/**
		获取图片对象
	*/
	public function createimgobj($path)
	{
		$ext	= $this->getmime($path);
		$img	= false;
		switch ($ext){
			case 'image/gif':
				$img=imagecreatefromgif($path);
			break;
			case 'image/png':
				$img=imagecreatefrompng($path);
			break;
			default:
				$img=imagecreatefromjpeg($path);
			break;
		}
		return $img;
	}
	
	public function conver($opath, $npath)
	{
		if(!file_exists($opath))return;
		$img 	= $this->createimgobj($opath);
		$this->saveas($npath, $img);
	}
	
	/**
		获取图片的格式
	*/
	public function getext($img_name)
	{
		$type = strtolower(substr($img_name,strrpos($img_name,'.')+1));
		return $type;
	}
	
	public function getmime($img_name)
	{ 
		$mime = '';
		if(file_exists($img_name)){
			$fileobj	= getimagesize($img_name);
			$mime		= strtolower($fileobj['mime']);
			$this->mime = $mime;
		}
		return $mime;
	}
	
	/**
		添加文字水印
		$str	添加文字
	*/
	public function addwater($str,$color='#000000',$size=20,$align='rb')
	{
		if(!$this->bool)return;
		$font	= '../fonts/FZZHYJW.TTF';	//方正稚艺简体
		$lw 	= strlen($str)*($size/2);
		$lh		= $size*0.5;
		$color	= $this->color($color,$this->img);
		$x		= 2;
		$y		= 2;
		switch($align){
			case 'rb'://右下角
				$x		= $this->w - $lw-2;
				$y		= $this->h - $lh-2;
			break;
			case 'tr'://右上角
				$x		= $this->w - $lw-2;
			break;
			case 'lb'://左下角
				$y		= $this->h - $lh-2;
			break;
			case 'cn'://居中
				$x		= ($this->w - $lw) * 0.5;
				$y		= ($this->h - $lh) * 0.5;
			break;
		}
		imagettftext($this->img, $size,0, $x, $y, $color, $font, $str);
		$sapath	= str_replace('.'.$this->ext.'', '_water.'.$this->ext.'', $this->path);
		$sapath	= $this->path;
		$this->saveas($sapath, $this->img);//另存为
	}
	
	/**
		添加图片水印
		@params	$align 图片位置
	*/
	public function imgwater($imgpath,$align='rb')
	{
		if(!$this->bool || !file_exists($imgpath))return;
		list($lw, $lh) 	= getimagesize($imgpath);
		$logoimg		= $this->createimgobj($imgpath);
		$x				= 2;
		$y				= 2;
		switch($align){
			case 'rb'://右下角
				$x		= $this->w - $lw-2;
				$y		= $this->h - $lh-2;
			break;
			case 'tr'://右上角
				$x		= $this->w - $lw-2;
			break;
			case 'lb'://左下角
				$y		= $this->h - $lh-2;
			break;
			case 'cn'://居中
				$x		= ($this->w - $lw) * 0.5;
				$y		= ($this->h - $lh) * 0.5;
			break;
		}
		imagecopymerge($this->img, $logoimg, $x ,$y ,0 ,0 ,$lw ,$lh, 100);
		$this->saveas($this->path, $this->img);
	}
	
	/**
		创建颜色
	*/
	public function color($color,$img)
	{
		if(!empty($color)&&(strlen($color)==7)){
			$r=hexdec(substr($color,1,2));
			$g=hexdec(substr($color,3,2));
			$b=hexdec(substr($color,5));
		}else{
			$r=$g=$b='00';
		}
		return imagecolorallocate($img, $r, $g, $b);
	}
	
	
	public function colorTorgb($color)
	{
		if(!empty($color)&&(strlen($color)==7)){
			$r=hexdec(substr($color,1,2));
			$g=hexdec(substr($color,3,2));
			$b=hexdec(substr($color,5));
		}else{
			$r=$g=$b='00';
		}
		return array($r, $g, $b);
	}
	
	public function colorToHsl($color)
	{
		$rgba = $this->colorTorgb($color);
		$r 	= floatval($rgba[0])/255;
        $g 	= floatval($rgba[1])/255;
        $b 	= floatval($rgba[2])/255;
        $rgb = array($r, $g, $b);
        $max = max($rgb);
        $min = min($rgb);
        $diff = $max - $min;
        if ($max == $min) {
            $h = 0;
        } else if ($max == $r && $g >= $b) {
            $h = 60 * (($g - $b) / $diff);
        } else if ($max == $r && $g < $b) {
            $h = 60 * (($g - $b) / $diff) + 360;
        } else if ($max == $g) {
            $h = 60 * (($b - $r) / $diff) + 120;
        } else if ($max == $b) {
            $h = 60 * (($r - $g) / $diff) + 240;
        }
        $l = ($max + $min) / 2;
        if ($l == 0 || $max == $min) {
            $s = 0;
        } else if (0 < $l && $l <= 0.5) {
            $s = $diff / (2 * $l);
        } else if ($l > 0.5) {
            $s = $diff / (2 - 2 * $l);
        }
		return array($h, round($s*100,2), round($l*100,2));
	}
	
	/**
		注销图片
	*/
	private function destroy()
	{
		imagedestroy($this->img);
	}
	
	/**
		另存图片
	*/
	private function saveas($spath,$img)
	{
		$ext = $this->getmime($spath);
		$this->saveass($ext, $img, $spath);		
	}
	private function saveass($ext,$img, $spath)
	{
		switch($ext){
			case 'image/gif':
				imagegif($img,$spath);
			break;
			case 'image/png':
				imagepng($img,$spath);
			break;
			case 'image/bmp':
				imagewbmp($img,$spath);
			break;
			default:
				imagejpeg($img,$spath,80); 
			break;
		}		
	}
	
	/**
		图片缩略图
		@param	$w 宽
		@param	$h 高
	*/
	public function thumbnail($w,$h,$lx=0)
	{
		if(!$this->bool)return '';
		list($mw, $mh, $bili) = $this->imgwh($w,$h);
		$tmpimg = imagecreatetruecolor($w,$h);
		imagefill($tmpimg,0,0,$this->color('#ffffff',$tmpimg));
		$tx	= 0;
		$ty	= 0;
		
		//开始截的位置
		$sx	= 0;
		$sy	= 0;
		
		if($w > $mw){
			if($lx==1){//整图缩略可以看到白边
				$tx = ($w-$mw)/2;
			}else if($lx == 0){//可能去掉看不到的
				$mw = $w;
				$mh	= $mw/$this->whbili;
				$nbl= $mh/$this->h;
				$sy = ($mh-$h)/2/$nbl;	//当前缩放比例
			}
		}
		if($h > $mh){
			if($lx==1){
				$ty	= ($h-$mh)/2;
			}else if($lx == 0){
				$mh = $h;
				$mw	= $mh*$this->whbili;
				$nbl= $mw/$this->w;
				$sx = ($mw-$w)/2/$nbl;	//当前缩放比例
			}
		}
		//imagecopyresized
		imagecopyresampled($tmpimg, $this->img, $tx,$ty, $sx,$sy, $mw,$mh,$this->w,$this->h);//生成缩略图
		//$sapath	= str_replace('.'.$this->ext.'', '_thumb'.$w.'x'.$h.'.'.$this->ext.'', $this->path);
		$sapath	= str_replace('.'.$this->ext.'', '_s.'.$this->ext.'', $this->path);
		$this->saveass($this->mime ,$tmpimg, $sapath);//保存图片
		return $sapath;
	}
	
	/**
		图片显示宽高
	*/	
	public function imgwh($mw,$mh)
	{
		$w = $this->w;
		$h = $this->h;
		$bili=1;
		if($w>$mw){
			$bili=($mw/$w);
			$h=$bili*$h;
			$w=$mw;
		}
		if($h>$mh){
			$bili=($mh/$this->h);
			$w=$bili*$this->w;
			$h=$mh;
		}
		return array($w,$h,$bili);
	}
}