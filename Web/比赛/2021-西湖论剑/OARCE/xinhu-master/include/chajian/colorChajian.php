<?php 
/**
	颜色操作
*/
class colorChajian extends Chajian{

	/**
		颜色
	*/
	public function color($color,$l=127.5)
	{
		$r=hexdec(substr($color,1,2));
		$g=hexdec(substr($color,3,2));
		$b=hexdec(substr($color,5));
		$yb=127.5;
		if($l > $yb){
			$l = $l - $yb;
			$r = ($r * ($yb - $l) + 255 * $l) / $yb;
			$g = ($g * ($yb - $l) + 255 * $l) / $yb;
			$b = ($b * ($yb - $l) + 255 * $l) / $yb;
		}else{
			$r = ($r * $l) / $yb;
			$g = ($g * $l) / $yb;
			$b = ($b * $l) / $yb;
		}
		$nr=$this->tohex($r);
		$ng=$this->tohex($g);
		$nb=$this->tohex($b);
		return '#'.$nr.$ng.$nb;
	}
	
	private function tohex($n)
	{
		$hexch = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
		$n 	= round($n);
		$l 	= $n % 16;
		$h 	= floor(($n / 16)) % 16;
		return ''.$hexch[$h].''.$hexch[$l].'';
	}
}