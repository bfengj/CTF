<?php
/**
*	电子合同小程序用的接口
*/
class openmodhetongClassAction extends openapiAction
{
	/**
	*	首页返回数据
	*/
	public function dataAction()
	{
		$mobile = $this->get('mobile');
		$xcytype = $this->get('xcytype');
		$openid = $this->get('openid');
		$nickName = $this->jm->base64decode($this->get('nickName'));
		$htdata = array();
		$db 	= m('wxxcyus');
		$uarr['mobile'] 	= $mobile;
		$uarr['xcytype'] 	= $xcytype;
		$uarr['openid'] 	= $openid;
		$uarr['nickName'] 	= $nickName;
		$uarr['province'] 	= $this->get('province');
		$uarr['city'] 		= $this->get('city');
		$uarr['gender'] 	= $this->get('gender');
		$uarr['dingyue'] 	= $this->get('dingyue');
		$uarr['avatarUrl'] 	= $this->jm->base64decode($this->get('avatarUrl'));
		$where = "`openid`='$openid'";
		if($db->rows($where)==0){
			$uarr['adddt'] = $this->now;
			$where='';
		}else{
			$uarr['optdt'] = $this->now;
		}
		$db->record($uarr, $where);
		
		$custid = '0'; //客户id
		$rows 	= m('customer')->getall("`mobile`='$mobile' and `status`=1",'id');
		foreach($rows as $k=>$rs)$custid.=','.$rs['id'].'';
		if($custid!='0'){
			$htrows = m('custract')->getall('custid in('.$custid.')','custid,custname,id,startdt,enddt,signdt,type,money,num','id desc');
			$dt 	= $this->rock->date;
			foreach($htrows as $k=>$rs){
				
				if($rs['startdt']>$dt){
					$statustext='待生效';
					$statuscolor='blue';
				}else if($rs['startdt']<=$dt && $rs['enddt']>=$dt){
					$statustext='生效中';
					$statuscolor='green';
				}else if($rs['enddt']<$dt){
					$statustext='已过期';
					$statuscolor='gray';
				}
				
				$htdata[] = array(
					'id' => $rs['id'],
					'name' => $rs['custname'],
					'modenum' => 'custract',
					'explain' => '金额:'.$rs['money'].',编号:'.$rs['num'].',有效期:'.$rs['startdt'].'→'.$rs['enddt'].'',
					'statustext'=>$statustext,
					'statuscolor'=>$statuscolor,
				);
			}
		}
		
		return returnsuccess(array(
			'htdata' => $htdata,
		));
	}
	
	/**
	*	获取客户数据
	*/
	public function customerAction()
	{
		$mobile  = $this->get('mobile');
		$xcytype = $this->get('xcytype');
		$openid  = $this->get('openid');
		$sql 	 = "select a.id,a.name,a.unitname,b.name as yewuname from [Q]customer a left join [Q]admin b on a.uid=b.id where a.`mobile`='$mobile' and a.`status`=1";
		$rows 	 = $this->db->getall($sql);
		$custdata = array();
		foreach($rows as $k=>$rs){
			$custdata[] = array(
				'id' => $rs['id'],
				'name' => $rs['name'],
				'modenum' => 'customer',
				'explain' => '业务员:'.$rs['yewuname'].'',
			);
		}
		
		return returnsuccess(array(
			'custdata' => $custdata,
		));
	}
}