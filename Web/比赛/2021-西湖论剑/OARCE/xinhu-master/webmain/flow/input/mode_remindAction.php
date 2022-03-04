<?php
/**
*	此文件是流程模块【remind.单据提醒设置】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_remind|input','flow')调用到对应方法
*/ 
class mode_remindClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$modenum = $arr['modenum'];
		$rows	 = array();
		$rows['table'] = m('flow_set')->getmou('`table`', "`num`='$modenum'");
		
		$where 	 = "`uid`='$this->adminid' and `modenum`='$modenum' and `mid`='".$arr['mid']."' and `id`<>$id";
		if($this->flow->rows($where)>0)return '你已设置这单据提醒了';
		
		return array(
			'rows' => $rows
		);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public function ratevalset()
	{
		$rate		= arrvalue($this->rs, 'rate');
		$rateval	= arrvalue($this->rs, 'rateval');;
		$str		= '<div id="pinlv">';
		
		$ratea		= explode(',', $rate);
		$rateb		= explode(',', $rateval);
		$len 		= count($ratea);
		$selarr['o'] = '仅一次';
		$selarr['h'] = '每小时';
		$selarr['d'] = '每天';
		$selarr['g'] = '每工作日';
		$selarr['x'] = '每休息日';
		$selarr['w1'] = '每周一';
		$selarr['w2'] = '每周二';
		$selarr['w3'] = '每周三';
		$selarr['w4'] = '每周四';
		$selarr['w5'] = '每周五';
		$selarr['w6'] = '每周六';
		$selarr['w7'] = '每周日';
		$selarr['m']  = '每月';
		$selarr['y']  = '每年';
		$isbr 		  = $this->rock->ismobile() ? '<br>' : '';
		for($i=0; $i<$len; $i++){
			$selstr	= '';
			$v1 	= $ratea[$i];
			$v2a 	= explode('|', $rateb[$i]);
			$v2 	= $v2a[0];
			$v3 	= arrvalue($v2a, 1);
			foreach($selarr as $k=>$v){
				$slde 	= ($k==$v1) ? 'selected' : '';
				$selstr.='<option value="'.$k.'" '.$slde.'>'.$v.'</option>';
			}
			
			$fontss = ($v1=='h')?'':'none';
			$stsnn  = ($i>0)? 'style="padding-top:10px;margin-top:10px;border-top:1px #cccccc solid"' : '';
			$str .= '<div '.$stsnn.'><select onchange="changerate(this)" style="width:auto" class="inputs" name="rave_pinlvs1">'.$selstr.'</select>';
			$str.= '<input onblur="changeblur(this)"  style="width:auto" class="inputs datesss" onclick="js.datechange(this,\'datetime\')" readonly value="'.$v2.'" name="rave_pinlvs2" type="text">'.$isbr.'<font style="display:'.$fontss.'">&nbsp;每天截止至&nbsp;<input onblur="changeblur2(this)"  style="width:80px" class="inputs datesss" onclick="js.datechange(this,\'time\')" readonly value="'.$v3.'" name="rave_pinlvs3" type="text"></font>'.$isbr.'<input type="button" onclick="changeadd(this)" value="＋" class="webbtn"><input onclick="changejian(this)" type="button" value="－" class="webbtn">&nbsp;<span></span>';
			$str.= '</div>';
		}
		
		$str .= '</div>';
		
		return $str;
	}
}	
			