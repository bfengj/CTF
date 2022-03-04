<?php
class locationClassModel extends Model
{
	
	public function add($user, $arr)
	{
		$uid = (int)m('admin')->getmou('id',"`user`='$user'");
		$arr['user'] = $user;
		$arr['uid']  = $uid;
		$arr['optdt']= $this->rock->now;
		$this->insert($arr);
	}
}