<?php
class totalsClassModel extends Model
{
	//首页统计的
	public function gettotals($uid)
	{
		$optdt 	= $this->rock->now;
		$arr 	= array();
		$bidb	= m('flowbill');
		
		$todo			= m('todo')->rows("uid='$uid' and `status`=0 and `tododt`<='$optdt'");
		$arr['todo']	= $todo;
		$arr['daiban']	= $bidb->daibanshu($uid);
		$arr['applywtg']= $bidb->applymywgt($uid);
		$arr['workwwc']	= m('work')->getwwctotals($uid);
		$arr['email']	= m('emailm')->wdtotal($uid);
		$arr['flowtodo']= m('flowtodo')->getwdtotals($uid);
		return $arr;
	}
}