<?php
class bookClassModel extends Model
{
	/**
	*	读取当前时间借阅数量
	*/
	public function getborrowshu($bookid, $dt='', $sid=0)
	{
		if($dt=='')$dt=$this->rock->date;
		$where = '`bookid`='.$bookid.' and `status` in(0,1) and `isgh`=0 and `id`<>'.$sid.'';
		//$where.= " and `jydt`<='$dt'";
		$tos 	= $this->db->rows('[Q]bookborrow', $where);
		return floatval($tos);
	}
	
	/**
	*	读取数可借阅数量
	*/
	public function getjieshu($bookid, $dt='', $sid=0)
	{
		$yij = $this->getborrowshu($bookid, $dt, $sid);
		$tos = floatval($this->getmou('shul', $bookid));
		
		return $tos-$yij;
	}
}