<?php
class flow_assetmClassModel extends flowModel
{
	public function initModel()
	{
		$this->statearr = c('array')->strtoarray('blue|闲置,#ff6600|在用,red|维修,gray|报废,gray|丢失');
	}
	
	public function flowrsreplace($rs)
	{
		if(isset($rs['typeid']))$rs['typeid'] 	= $this->db->getmou('[Q]option','name',"`id`='".$rs['typeid']."'");
		if(isset($rs['ckid']) && $rs['ckid']>0){
			$rs['ckid'] 	= $this->db->getmou('[Q]option','name',"`id`='".$rs['ckid']."'");
			if(isset($rs['address']) && isempt($rs['address'])){
				$rs['address'] = $rs['ckid'];
				$this->update("`address`='".$rs['address']."'", $rs['id']);
			}
		}
		if(isset($this->statearr[$rs['state']])){
			$b 			 = $this->statearr[$rs['state']];
			$rs['state'] = '<font color="'.$b[0].'">'.$b[1].'</font>';
		}
		if(isset($rs['fengmian']) && !isempt($rs['fengmian']))$rs['fengmian'] = '<img src="'.$rs['fengmian'].'" height="100">';
		return $rs;
	}

	protected function flowbillwhere($uid, $lx)
	{
		$where  = '';
		$typeid = $this->rock->post('typeid','0');
		//$key 	= $this->rock->post('key');
		if($typeid!='0'){
			$alltpeid = m('option')->getalldownid($typeid);
			$where .= ' and `typeid` in('.$alltpeid.')';
		}
		//弃用这个，去到表单表示元素管理开启搜索
		//if($key != '')$where.=" and (`title` like '%$key%' or `num` like '%$key%' or `usename` like '%$key%')";
		return array(
			'where' => $where,
			'orlikefields'=>'title,num,usename',
			'order' => 'optdt desc',
			//'fields'=> 'id,title,num,brand,optdt,usename,state,ckid'
		);
	}
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		return array(
			'typeid' 		=> '电脑/台式电脑',
			'title' 		=> '这是一个电脑啊',
			'num' 		=> 'ZiCAN-001',
			'brand' 		=> '联想',
			'laiyuan' 		=> '购买',
			'buydt' 		=> '2017-01-17',
			'explain' 		=> '简单说明一下',
			'address'		=> '放哪里了',
		);
	}
	
	//导入之前
	public function flowdaorubefore($rows)
	{
		foreach($rows as $k=>$rs){
			$rows[$k]['typeid'] 	= $this->option->gettypeid('assetstype',$rs['typeid']);
			$rows[$k]['adddt']		= $this->rock->now;
			$rows[$k]['optdt']		= $this->rock->now;
		}
		return $rows;
	}
}