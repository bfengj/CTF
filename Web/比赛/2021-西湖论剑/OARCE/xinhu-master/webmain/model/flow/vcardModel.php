<?php
class flow_vcardClassModel extends flowModel
{

	protected function flowbillwhere($uid, $lx)
	{
		$where 	= '`uid`='.$uid.'';
		$key 	= $this->rock->post('key');
		$gname 	= $this->rock->post('gname');
		if(!isempt($gname))$where.=" and `gname`='$gname'";
		if(!isempt($key))$where.=" and (`name` like '%$key%' or `unitname` like '%$key%' or `mobile` like '%$key%' or `gname` = '$key')";
	
		return array(
			'where' => 'and '.$where,
		);
	}
	
	//替换
	public function flowrsreplace($rs, $lx=0)
	{
		if($lx==2 && $this->rock->ismobile()){
			if(!isempt($rs['mobile']))$rs['mobile']='<a onclick="return callPhone(this)" href="tel:'.$rs['mobile'].'">'.$rs['mobile'].'</a>';
			if(!isempt($rs['tel']))$rs['tel']='<a  onclick="return callPhone(this)" href="tel:'.$rs['tel'].'">'.$rs['tel'].'</a>';
		}
		return $rs;
	}
	
	public function flowdaorutestdata()
	{
		return array(
			'name' 		=> '关羽',
			'sex' 		=> '男',
			'mobile' 	=> '15812345678',
			'tel' 	=> '0592-123456',
			'unitname' 	=> '蜀国荆州守将',
			'email' 	=> 'guanyu@rockoa.com',
			'gname' 	=> '同事',
			'address' 	=> '荆州市',
		);
	}
}