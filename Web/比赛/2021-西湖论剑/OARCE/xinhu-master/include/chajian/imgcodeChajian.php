<?php 
/**
*	图片验证码
*/
class imgcodeChajian extends Chajian{
	
	public function check($key,$val)
	{
		if(isempt($val))return false;
		$code 	= 'abc'.$val.'';
		$geval  = c('cache')->get('code'.$key.'');
		if(md5($code)!=$geval)return false;
		c('cache')->del('code'.$key.'');
		return true;
	}
	
	public function show($key)
	{
		header("Content-type:image/gif");
		$a		= rand(0,9);
		$b		= rand(0,9);
		$h  	= 30;
		$code 	= 'abc'.($a+$b).'';
		$w 		= 70;
		$im		= imagecreatetruecolor($w,$h);
		c('cache')->set('code'.$key.'', md5($code), 5*60);
		$bg		= imagecolorallocate($im,255,255,255);
		imagefill($im,0,0,$bg);	//添加背景颜色

		$black	= imagecolorallocate($im,0,0,0);

		for($i=0;$i<2;$i++){//画线条
			$at1=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imageline($im,0,rand(0,$h),$w,rand(0,$h),$at1);
		}

		for($i=0;$i<200;$i++){//画点
			$at1=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($im,rand(0,$w),rand(0,$h),$at1);
		}

		imagestring($im,5,rand(0,30),rand(0,$h-15),''.$a.'+'.$b.'=?',$black);
		
		imagegif($im);
		imagedestroy($im);
	}
}                               