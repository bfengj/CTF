<?php
class flow_customerClassModel extends flowModel
{
	public function initModel()
	{
		$this->statearr		 = c('array')->strtoarray('停用|#888888,启用|green');
		$this->statarr		 = c('array')->strtoarray('否|#888888,是|#ff6600');
	}
	
	//高级搜索下
	public function flowsearchfields()
	{
		$arr[] = array('name'=>'所属人...','fields'=>'uid');
		$arr[] = array('name'=>'创建人...','fields'=>'createid');
		return $arr;
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		if(isset($rs['status'])){
			if($rs['status']==0)$rs['ishui'] = 1;
			$zt 	= $this->statearr[$rs['status']];
			$rs['statuss']	= $rs['status'];
			$rs['status']	= '<font color="'.$zt[1].'">'.$zt[0].'</font>';
		}
		
		if(isset($rs['isstat'])){
			$stat 	= $this->statarr[$rs['isstat']];
			$rs['isstat']	= '<font color="'.$stat[1].'">'.$stat[0].'</font>';
		}
		
		if(isset($rs['isgys'])){
			$gys 	= $this->statarr[$rs['isgys']];
			$rs['isgys']	= '<font color="'.$gys[1].'">'.$gys[0].'</font>';
		}
		if($this->rock->arrvalue($rs,'htshu','0')==0)$rs['htshu']='';
		if($this->rock->arrvalue($rs,'moneyz','0')==0)$rs['moneyz']='';
		if($this->rock->arrvalue($rs,'moneyd','0')==0)$rs['moneyd']='';
		
		if($lx==1){
			//$rs['suoname'] = $this->adminmodel->getmou('name','id='.$rs['uid'].'');
			
		}
		
		//详情时，移动端
		if($lx==1 && $this->rock->ismobile()){
			if(!isempt($rs['mobile']))$rs['mobile']='<a onclick="return callPhone(this)" href="tel:'.$rs['mobile'].'">'.$rs['mobile'].'</a>';
			if(!isempt($rs['tel']))$rs['tel']='<a onclick="return callPhone(this)" href="tel:'.$rs['tel'].'">'.$rs['tel'].'</a>';
		}
		
		return $rs;
	}
	
	
	
	//是否有查看权限
	protected function flowisreadqx()
	{
		$bo = false;
		$shateid = ','.$this->rs['shateid'].',';
		if(contain($shateid,','.$this->adminid.','))$bo=true;
		return $bo;
	}
	
	protected function flowgetfields_qiyong($lx)
	{
		$arr = array();
		if($this->uid==$this->adminid){
			$arr['mobile'] 		= '手机号';
			$arr['tel'] 		= '电话';
			$arr['email'] 		= '邮箱';
			$arr['routeline'] 	= '交通路线';
		}
		return $arr;
	}

	
	protected function flowoptmenu($ors, $crs)
	{
		$zt  = $ors['statusvalue'];
		$num = $ors['num'];
		if($num=='ztqh'){
			$this->update('`status`='.$zt.'', $this->id);
		}
		
		//共享
		if($num=='shate'){
			$cname 	 = $crs['cname'];
			$cnameid = $crs['cnameid'];
			$this->update(array(
				'shateid' 	=> $cnameid,
				'shate' 	=> $cname,
			), $this->id);
			$this->push($cnameid, '客户管理', ''.$this->adminname.'将一个客户【{name}】共享给你');
		}
		
		//取消共享
		if($num=='unshate'){
			$this->update(array(
				'shateid' 	=> '',
				'shate' 	=> '',
			), $this->id);
		}
		
		//放入公海
		if($num=='ghnoup'){
			$this->update(array(
				'isgh' 	=> '1',
				'uid' 	=> 0,
				'suoname'=>''
			), $this->id);
		}
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		return array(
			'order' => '`status` desc,`optdt` desc',
			//'table'	=> '`[Q]'.$this->mtable.'` a left join `[Q]admin` b on a.`uid`=b.id',
			//'asqom' => 'a.',
			//'fields'=> 'a.*,b.name as suoname'
		);
	}
	
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		$barr = array(
			'name' 		=> '信呼',
			'type' 		=> '软件',
			'laiyuan' 		=> '网上开拓',
			'unitname' 		=> '厦门信呼科技有限公司',
			'tel' 		=> '0592-123456',
			'mobile' 		=> '15800000000',
			'email' 		=> 'admin@rockoa.com',
			'address' 		=> '福建厦门思明区软件园',
			'linkname' 		=> '磐石',
			'isgh' 		=> '是',
		);
		$barr1 = array(
			'name' 		=> '百度',
			'type' 		=> '搜索计算',
			'laiyuan' 		=> '电话联系',
			'unitname' 		=> '百度在线网络技术(北京)有限公司',
			'tel' 		=> '010-123456',
			'mobile' 		=> '15800000001',
			'email' 		=> 'admin@baidu.com',
			'address' 		=> '北京软件园百度大厦',
			'linkname' 		=> '李彦宏',
			'isgh' 		=> '否',
		);
		$barr2 = array(
			'name' 		=> '陈先生',
			'type' 		=> '个人',
			'laiyuan' 		=> '电话联系',
			'unitname' 		=> '',
			'tel' 		=> '010-123456',
			'mobile' 		=> '15800000002',
			'email' 		=> '1111@qq.com',
			'address' 		=> '福建厦门火车站',
			'linkname' 		=> '',
			'isgh' 			=> '否',
		);
		return array($barr,$barr1,$barr2);
	}

	public function flowdaorubefore($data)
	{
		$arr = array();
		$dbs = m('admin');
		foreach($data as $k=>$rs){
			$isgh 	= (arrvalue($rs,'isgh')=='是') ? 1: 0 ;
			$isstat = (arrvalue($rs,'isstat')=='是') ? 1: 0 ;
			if(isset($rs['status'])){
				$rs['status'] = (arrvalue($rs,'status')=='启用') ? 1: 0 ;
			}
			if(isset($rs['suoname'])){
				if($isgh==0){
					$urs = $dbs->geturs('name:'.$rs['suoname']);
					if($urs)$rs['uid'] = $urs['id'];
				}
				//unset($rs['suoname']);
			}
			$rs['isgh'] 	= $isgh;
			$rs['isstat'] 	= $isstat;
			if($isgh==1)$rs['uid'] = 0; 
			
			$arr[] = $rs;
		}
		return $arr;
	}
	
	
	/**
	*	自动放入公海
	*/
	public function addgonghai()
	{
		$tshu	= (int)$this->option->getval('crmaddghai','0');
		if($tshu<=0)return;
		$sneuar	= array();
		
		$rows 	= $this->getall('`uid`>0 and `htshu`=0 and `isgys`=0 and `id` not in(select `custid` from `[Q]custsale` where `state` in(1)) and `id` not in(select `custid` from `[Q]goodm` where `type`=2 and `status` in(0,1))','lastdt,optdt,id,name,uid,unitname');
		$dtobj 	= c('date');
		$addghs = array();
		foreach($rows as $k=>$rs){
			$lastdt = $rs['lastdt'];
			if(isempt($lastdt))$lastdt = $rs['optdt'];
			$jg   = $dtobj->datediff('d', $lastdt, $this->rock->now);
			
			if($jg > $tshu){
				$sneuar[$rs['uid']][] = '['.$rs['name'].']超'.$jg.'天未跟进已放入公海库';
				$addghs[] = $rs['id'];
			}else{
				//要放入之前2天提醒
				$ts = $tshu - $jg;
				if($ts<3)$sneuar[$rs['uid']][] = '['.$rs['name'].']将'.$ts.'天后放入公海库';
			}
		}
		
		//通知给对应人
		$maxlen = 5;
		foreach($sneuar as $uid=>$ursa){
			$str = '';
			foreach($ursa as $k1=>$s1){
				if($str!='')$str.="\n";
				if($k1>=$maxlen){
					$str.='还有'.(count($ursa)-$maxlen).'条，点击查看更多';
					break;
				}
				$str.="".$s1."";
			}
			$this->pushs($uid, $str, '客户未跟进提醒', array(
				'wxurl' => $this->getwxurl()
			));
		}
		if($addghs){
			$sid = join(',', $addghs);
			$this->update("`uid`=0,`isgh`=1", "`id` in($sid)");
		}
	}
	
	//对外的详情页
	public function flowopenxiang($da, $xiangdata)
	{
		$zdarr = array('name','type','laiyuan','unitname','tel','mobile','sheng','shi','address','routeline','shibieid','openbank','cardid','explain','linkname');
		$slsts = array();
		foreach($xiangdata as $k=>$rs){
			if(in_array($rs['fields'], $zdarr)){
				$slsts[] = $rs;
			}
		}
		return array('xiangdata'=>$slsts,'modename'=>'客户详情');
	}
}