<?php 
class dateChajian extends Chajian
{
	public $now;
	public $date;
	
	protected function initChajian()
	{
		$this->now 		= $this->rock->now;
		$this->date 	= $this->rock->date;
	}

	public function formatdt($dt='',$format='Y-m-d H:i:s')
	{
		return date($format,strtotime($dt));
	}
	
	/**
		获取上月
	*/
	public function lastmonth($dt, $type='Y-m')
	{
		return $this->adddate($dt,'m',-1,$type);
	}

	/**
		计算时间间隔
	*/	
	public function datediff($type,$start,$end)
	{
		$time1 = strtotime($start);
		$time2 = strtotime($end);
		$val=0;
		switch($type){
			case 'Y'://年
				$Y1 = date('Y',$time1);
				$Y2 = date('Y',$time2);
				$val = $Y2 - $Y1;
			break;
			case 'm'://月份
				$y1 = date('Y',$time1);
				$y2 = date('Y',$time2);
				$m1 = date('m',$time1);
				$m2 = date('m',$time2);
				$y = $y1 - $y2;
				$mz = 0;
				if($y1 == $y2){
					$mz=$m2-$m1;
				}elseif($y1<$y2){
					$mz = 12-$m1+$m2+12*($y2-$y1-1);
				}else{
					$mz = -(12-$m2+$m1+12*($y1-$y2-1));
				}
				$val = $mz;
			break;
			case 'd'://日
				$dt1 = strtotime(date('Y-m-d',$time1));
				$dt2 = strtotime(date('Y-m-d',$time2));
				$time=$dt2-$dt1;
				$val = $time/3600/24;
			break;
			case 'H'://小时
				$time = $time2 - $time1;
				$val = floor($time/3600);
			break;
			case 'i'://分钟
				$time = $time2 - $time1;
				$val = floor($time/60);
			break;
			case 's'://秒
				$val = $time2 - $time1;
			break;
		}
		return $val;
	}
	
	/**
		时间计算添加
	*/
	public function adddate($dt,$lx,$v=0,$type='')
	{
		$time	= strtotime($dt);
		$arrn1	= explode(' ',$dt);
		$arrn	= explode('-',$arrn1[0]);
		$Y		= (int)$arrn[0];
		$m		= (int)$arrn[1];
		$d		= (int)$arrn[2];
		$H=$i=$s=0;
		if($this->contain($dt,':')){
			$arrn2	= explode(':',$arrn1[1]);
			$H		= (int)$arrn2[0];
			$i		= (int)$arrn2[1];
			$s		= (int)$arrn2[2];
		}
		$rval	= $dt;
		if($type=='')$type=($H==0)?'Y-m-d':'Y-m-d H:i:s';
		if($type=='datetime')$type='Y-m-d H:i:s';
		if($v ==0)return date($type, $time);
		switch($lx){
			case 'm'://月份
				$time	= mktime($H, $i, $s, $m+$v, $d, $Y);
			break;
			case 'Y'://年
				$time	= mktime($H, $i, $s, $m, $d, $Y+$v);
			break;
			case 'd'://日期
				$time	= mktime($H, $i, $s, $m, $d+$v, $Y);
			break;
			case 'H'://时
				$time	= mktime($H+$v, $i, $s, $m, $d, $Y);
			break;
			case 'i'://分
				$time	= mktime($H, $i+$v, $s, $m, $d, $Y);
			break;
			case 's'://秒
				$time	= mktime($H, $i, $s+$v, $m, $d, $Y);
			break;
		}
		$rval	= date($type,$time);
		return $rval;
	}
	
	//是否包含返回bool
	public function contain($str,$a)
	{
		return $this->rock->contain($str,$a);
	}
	
	//判断是否为空
	public function isempt($str)
	{
		return $this->rock->isempt($str);
	}	
	
	public function diffstr($start, $end, $str, $lx=0, $restr='')
	{
		$time1 	= strtotime($start);
		$time2 	= strtotime($end);
		$sj		= $time1-$time2;
		if($lx==1 && $sj<=0)return '';
		return $this->sjdate($sj, $str, $restr);
	}
	
	public function sjdate($sj, $str='', $restr='')
	{
		$h 	= $i = $s = $d = 0;
		$d 	= floor($sj/3600/24);
		$sj = $sj - $d * 3600 * 24;
		$h 	= floor($sj/3600);
		$sj = $sj - $h*3600;
		$i 	= floor($sj/60);
		$s  = $sj - $i * 60;
		$str = str_replace(array('d','H','i','s'),array($d,$h,$i,$s), $str);
		if($restr!=''){
			$resta = explode(',', $restr);
			foreach($resta as $restas)$str = str_replace($restas,'', $str);
		}
		return $str;
	}
	
	public function isdate($dt)
	{
		$bo	= false;
		if($this->isempt($dt))return $bo;
		$arr	= explode('-', $dt);
		if(count($arr)>2)$bo = true;
		$len 	= strlen($dt);
		if($len>10){
			$sfm = explode(' ', $dt);
			if(!isset($sfm[1]))return false;
			$arr = explode(':', $sfm[1]);
			if(count($arr)<2)return false;
		}
		return $bo;
	}
	
	/**
		返回月份最大日期
	*/
	public function getenddt($month)
	{
		$month	= substr($month,0,7);
		$max 	= $this->getmaxdt($month);
		return  ''.$month.'-'.$max.'';
	}
	
	public function getmaxdt($dt)
	{
		$d 	= explode('-', $dt);
		$m	= (int)$d[1];
		$y	= (int)$d[0];
		$a	= array(31,28,31,30,31,30,31,31,30,31,30,31);
		$d	= $a[$m-1];
		if($y%4 == 0 && $m==2 && $y%100 != 0)$d++;
		return $d;
	}
	
	public function cnweek($date)
	{
		$arr = array('日','一','二','三','四','五','六');
		return $arr[date('w', strtotime($date))];
	}
	
	//读取本周日期
	public function getweekarr($dt)
	{
		$w = date('w', strtotime($dt));
		$a = array(-6,0,-1,-2,-3,-4,-5);
		$oi   = $a[$w];
		$le	  = $oi+7;
		for($j=$oi; $j<$le; $j++){
			$arr[] = $this->adddate($dt, 'd', $j);
		}
		return $arr;
	}
	
	public function getweekfirst($dt)
	{
		$arr = $this->getweekarr($dt);
		return $arr[0];
	}
	
	public function getweeklast($dt)
	{
		$arr = $this->getweekarr($dt);
		return $arr[6];
	}
	
	/**
		计算返回当前间隔分析：今天 10:20
	*/
	public function stringdt($dttime, $type='G H:i')
	{
		$s 	= '';$H=$s=$i='00';
		$dts= explode(' ', $dttime);
		$yms= explode('-', $dts[0]);
		$Y 	= $yms[0];$m = $yms[1];$d = $yms[2];
		$jg = $this->datediff('d', $dts[0], $this->date);
		$G	= '';
		if($jg==0)$G='今天';
		if($jg==1)$G='昨天';
		if($jg==2)$G='前天';
		if($jg==-1)$G='明天';
		if($jg==-2)$G='后天';
		$A = $G;
		if($G=='')$G=substr($dts[0], 5);
		if($A=='')$A=$dts[0];
		$w	 = $this->cnweek($dts[0]);
		if(isset($dts[1])){
			$sjs = explode(':', $dts[1]);
			$H 	 = $sjs[0];
			if(isset($sjs[1]))$i = $sjs[1];
			if(isset($sjs[2]))$s = $sjs[2];
		}
		$str = str_replace(
			array('A','G','H','i','s','w','Y','m','d'),
			array($A,$G,$H,$i,$s,$w, $Y, $m, $d),
		$type);
		return $str;
	}
	
	
	/**
	*	计算周期$rate:d1,d2,$dt开始时间
	*	返回日期，根据日期判断是不是今天
	*/
	public function daterate($rate, $dt, $nowdt='')
	{
		if(isempt($rate) || isempt($dt))return false;//没有周期
		$dt 	= substr($dt,0, 10);//日期的类型
		if($nowdt=='')$nowdt 	= $this->rock->date;
		$nowdt	= substr($nowdt, 0, 10);
		$jg 	= str_replace(array('m','d','w','y'),array('','','',''),$rate);
		if($jg=='')$jg='1';
		$jg = (int)$jg;
		$lx = substr($rate, 0, 1);
		
		if($lx=='d'){
			$jge = $this->datediff('d', $dt, $nowdt);
			if($jge % $jg==0 || $jge==0){
				return $nowdt;
			}
		}
		
		//每月
		if($lx=='m'){
			$jge = $this->datediff('m', $dt, $nowdt);
			if($jge % $jg==0 || $jge==0){
				$ndt = date('Y-m-'.substr($dt, 8).'');
				if($ndt==$nowdt)return $nowdt;
			}
		}
		
		//每年
		if($lx=='y'){
			$jge = $this->datediff('y', $dt, $nowdt);
			if($jge % $jg==0 || $jge==0){
				$ndt = date('Y-'.substr($dt, 5).'');
				if($ndt==$nowdt)return $nowdt;
			}
		}
		
		//每周
		if($lx=='w'){
			$w 		= (int)date('w', strtotime($nowdt));if($w==0)$w=7;//星期7
			if($w==$jg){
				return $nowdt;
			}
		}
		
		return false;
	}
}                                    