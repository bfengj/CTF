<?php
class txcloud_renlianClassModel extends txcloudModel
{
	
	public function inittxCloud()
	{
		$this->settable('wxtx_renlian');
		$this->groupid = $this->option->getval('txcloud_rlroupid');
	}
	
	
	

	/**
	*	人脸检测
	*/
	public function DetectFace($url)
	{
		$chul  = $this->DetectFaceInit($url);
		$barr = $this->send('DetectFace', $chul[0], $chul[1]);
		return $barr;
	}
	private function DetectFaceInit($url)
	{
		$chul  = $chul1 = array();
		if(substr($url,0,4)=='http'){
			$chul['Url'] = $url;
		}else{
			$chul['Image']  = base64_encode(file_get_contents($url));
			$chul1['Image'] = $chul['Image'];
		}
		return array($chul, $chul1);
	}
	
	
	
	/**
	*	获取
	*/
	public function GetPersonList()
	{
		$barr = $this->send('GetPersonList', array(
			'GroupId' => $this->groupid,
			'Limit'	  => 1000
		));
		if($barr['success']){
			$data = $barr['data'];
			$PersonInfos = $data['PersonInfos'];
			$ids  = '0';
			$ren  = 0;
			foreach($PersonInfos as $k=>$rs){
				$ren++;
				$PersonId = $rs['PersonId'];
				$where	  = "`personid`='$PersonId'";
				$id1	  = (int)$this->getmou('id', $where);
				if($id1==0)$where='';
				$this->record(array(
					'personid' => $PersonId,
					'personname' => $rs['PersonName'],
					'gender' => $rs['Gender'],
					'faceids' => join(',', $rs['FaceIds'])
				), $where);
				if($id1==0)$id1 = $this->db->insert_id();
				$ids .= ','.$id1.'';
			}
			$this->delete("`id` not in($ids)");
			return returnsuccess('共获取到'.$ren.'人');
		}
		return $barr;
	}
	
	public function GetGroupList()
	{
		$barr = $this->send('GetGroupList');
		return $barr;
	}
	
	/**
	*	删除人脸用户
	*/
	public function deleteRenlian($id)
	{
		$rs = $this->getone("`id`='$id'");
		if(!$rs)return returnerror('不存在');
		
		$barr = $this->send('DeletePerson', array(
			'PersonId' => $rs['personid']
		));
		if($barr['success']){
			$this->delete($id);
		}
		return $barr;
	}
	
	/**
	*	创建人脸用户
	*/
	public function createUser($arr)
	{
		$id		= $arr['id'];
		$uid	= $arr['uid'];
		$personname	= $arr['personname'];
		$imgurl 	= $arr['imgurl'];
		if($this->rows("`uid`='$uid' and `id`<>'$id'")>0)return returnerror('该用户已经创建过了');
		$urs 	= m('admin')->getone("`id`='$uid' and `status`=1");
		if(!$urs)return returnerror('该用户不存在/停用了,请到用户管理查看');
		
		if($id==0 && isempt($imgurl))return returnerror('请选择人脸图片地址');
		if(!isempt($imgurl) && substr($imgurl,0,4)!='http'){
			if(!file_exists($imgurl))return returnerror('人脸图片不存在');
			list($width, $height) = getimagesize($imgurl); 
			if($width< 60 || $height<60)return returnerror('人脸图片像素至少60x60，当前:'.$width.'x'.$height.'');
			$size = filesize($imgurl);
			if($size>1*1024*1024)return returnerror('人脸图片不能大于1M');
		}
		
		//检测人脸是否可以
		$barr = $this->DetectFace($imgurl);
		if(!$barr['success'])return $barr;
		
		
		if($id==0){
			$can  	= $this->DetectFaceInit($imgurl);
			$params = $can[0];
			$params['GroupId'] 		= $this->groupid;
			$params['PersonName'] 	= $personname;//姓名
			$params['PersonId'] 	= 'xinhu'.$urs['user'].'';
			$params['Gender'] 		= $urs['sex']=='男' ? '1': '2';
			
			//接口创建
			$barr = $this->send('CreatePerson',$params, $can[1]);
			if(!$barr)return $barr;
	
			
			$id = $this->insert(array(
				'personname'=> $personname,
				'personid'	=> $params['PersonId'],
				'gender'	=> $params['Gender'],
				'uid'		=> $uid,
				'imgurl'	=> $imgurl,
				'faceids'	=> $barr['data']['FaceId'],
			));
		}else{
			
			
		}
		
		return returnsuccess();
	}
	
	
	/**
	*	人脸搜索，用刷脸登录
	*/
	public function SearchFaces()
	{
		$can  	= $this->DetectFaceInit('images/wo.png');
		$params = $can[0];
		$params['GroupIds.0'] = $this->groupid;
		$barr = $this->send('SearchFaces', $params, $can[1]);
		
		return $barr;
	}
}