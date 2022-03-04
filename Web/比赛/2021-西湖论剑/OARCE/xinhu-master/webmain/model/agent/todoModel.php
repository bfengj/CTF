<?php
/**
*	消息提醒的应用
*/
class agent_todoClassModel extends agentModel
{
	public function initModel()
	{
		$this->settable('todo');
	}
	
	public function gettotal()
	{
		$stotal	= $this->getdbtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getdbtotal($uid)
	{
		$optdt 	= $this->rock->now;
		$stotal	= $this->rows("uid='$uid' and `status`=0 and `tododt`<='$optdt'");
		return $stotal;
	}
	
	protected function agenttotals($uid)
	{
		return array(
			'wdtodo' => $this->getdbtotal($uid)
		);
	}
	
	//数据读取应用
	protected function agentdata($uid, $lx)
	{
		$where = '';
		if($lx=='wdtodo')$where='and `status`=0';
		if($lx=='allydu'){
			$this->update("`status`=1", "uid='$uid' and `status`=0");
		}
		$key = $this->rock->post('key');
		if(!isempt($key))$where.=" and (`title`='$key' or `mess` like '%$key%')"; //关键词搜索
		
		$arr = $this->getlimit("uid='$uid' $where", $this->page,'*', 'id desc', $this->limit);
		$rows = $arr['rows'];
		$darr = array();
		foreach($rows as $k=>$rs){
			$statustext = '已读';
			$statuscolor = '#aaaaaa';
			if($rs['status']=='0'){
				$statustext = '未读';
				$statuscolor = 'red';
			}
			
			$cont   = $rs['mess'];
			
			$xiangurl   = '';
			if(!isempt($rs['modenum']) && !isempt($rs['mid']) && $rs['mid']>'0'){
				$xiangurl = 'task.php?a=x&num='.$rs['modenum'].'&mid='.$rs['mid'].'';
			}
			
			$darr[] = array(
				//'id' => $rs['id'],
				'optdt' => $rs['optdt'],
				'title' => $rs['title'],
				'cont' => $cont,
				'xiangurl' => $xiangurl,
				'ishui' => ($rs['status']=='1')?1:0,
				'statustext'=>$statustext,
				'statuscolor'=>$statuscolor,
			);
		}
		$arr['rows'] = $darr;
		return $arr;
	}
}