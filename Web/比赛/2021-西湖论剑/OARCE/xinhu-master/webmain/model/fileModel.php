<?php
class fileClassModel extends Model
{
	
	public function initModel()
	{
		$this->fileall = ',aac,ace,ai,ain,amr,app,arj,asf,asp,aspx,av,avi,bin,bmp,cab,cad,cat,cdr,chm,com,css,cur,dat,db,dll,dmv,doc,docx,dot,dps,dpt,dwg,dxf,emf,eps,et,ett,exe,fla,ftp,gif,hlp,htm,html,icl,ico,img,inf,ini,iso,jpeg,jpg,js,m3u,max,mdb,mde,mht,mid,midi,mov,mp3,mp4,mpeg,mpg,msi,nrg,ocx,ogg,ogm,pdf,php,png,pot,ppt,pptx,psd,pub,qt,ra,ram,rar,rm,rmvb,rtf,swf,tar,tif,tiff,txt,url,vbs,vsd,vss,vst,wav,wave,wm,wma,wmd,wmf,wmv,wps,wpt,wz,xls,xlsx,xlt,xml,zip,';
		
		$this->mimitype =  c('file')->getAllMime();
	}
	
	/***
	*	添加预览和下载记录
	*/
	public function addlogs($fileid, $type)
	{
		$uarr = array();
		$uarr['fileid'] = $fileid;
		$uarr['type'] = $type;
		$uarr['optname'] = $this->adminname;
		$uarr['optid'] = $this->adminid;
		$uarr['ip'] 	= $this->rock->ip;
		$uarr['web'] 	= $this->rock->web;
		$uarr['optdt'] 	= $this->rock->now;
		return m('files')->insert($uarr);
	}
	
	public function getmime($lx)
	{
		if(!isset($this->mimitype[$lx]))$lx = 'unkown';
		return $this->mimitype[$lx];
	}
	
	public function getfile($mtype, $mid, $where='')
	{
		if($where=='')$where = "`mtype`='$mtype' and `mid` in($mid)";
		$rows	= $this->getall("$where order by `id`");
		return $rows;
	}
	
	public function addfile($fileid, $mtype, $mid, $mknum='')
	{
		if(!$this->isempt($fileid)){
			$this->update("`mtype`='$mtype',`mid`='$mid',`mknum`='$mknum'", "`id` in($fileid) and `mid`=0");
		}
	}
	
	public function getstr($mtype, $mid, $lx=0, $where='')
	{
		$filearr 	= $this->getfile($mtype, $mid, $where);
		$fstr		= $this->getstrstr($filearr, $lx);
		return $fstr;
	}
	
	public function getstrstr($filearr, $lx)
	{
		$fstr		= '';
		if($filearr)foreach($filearr as $k=>$rs){
			if($k>0)$fstr.='<br>';
			$fstr .= $this->getfilestr($rs, $lx);
		}
		return $fstr;
	}
	
	//获取聚合文件
	public function getallstr($filearr, $mid, $lx=0)
	{
		$farr = array();
		if($filearr)foreach($filearr as $k=>$rs){
			if($rs['mid']==$mid)$farr[] = $rs;
		}
		return $this->getstrstr($farr, $lx);
	}
	
	public function isimg($ext)
	{
		return $this->contain('|jpg|png|gif|bmp|jpeg|', '|'.$ext.'|');
	}
	
	public function isoffice($ext)
	{
		return contain('|doc|docx|xls|xlsx|ppt|pptx|pdf|', '|'.$ext.'|');
	}
	
	public function isbianju($ext)
	{
		return contain('|doc|docx|xls|xlsx|ppt|pptx|', '|'.$ext.'|');
	}
	
	public function isyulan($ext)
	{
		return contain(',txt,log,html,htm,js,php,php3,mp4,md,cs,sql,java,json,css,asp,aspx,shtml,cpp,c,vbs,jsp,xml,bat,ini,conf,sh,', ','.$ext.',');
	}
	
	//判断是否可预览
	public function isview($ext)
	{
		if($this->isimg($ext))return true;
		if($this->isoffice($ext))return true;
		if($this->isyulan($ext))return true;
		return contain(',mp3,ogg,mp4,', ','.$ext.',');
	}
	
	//获取缩略图的路径
	public function getthumbpath($rs)
	{
		$thumbpath = $this->rock->repempt(arrvalue($rs, 'thumbpath'));
		if(!isempt($thumbpath)){
			if(substr($thumbpath,0,4)=='http')return $thumbpath;
			if(!file_exists($thumbpath))$thumbpath='';
		}
		
		if(isempt($thumbpath))$thumbpath = arrvalue($rs, 'thumbplat');
		if(!isempt($thumbpath)){
			$thumbpath = str_replace('{FILEURL}', getconfig('rockfile_url'), $thumbpath);
			$thumbpath = $this->rock->gethttppath($thumbpath);
		}
		return $thumbpath;
	}
	
	
	//$lx=2详情,$lx=3是flow.php getdatalog下读取的
	public function getfilestr($rs, $lx=0)
	{
		$fstr= '';
		if(!$rs)return $fstr;
		$str = $this->rock->jm->strrocktoken(array('a'=>'down','id'=>$rs['id']));
		$url = ''.URL.'index.php?rocktoken='.$str.'';
		$str = 'href="'.$url.'"';
		$ext   = $rs['fileext'];
		$id    = $rs['id'];
		$isimg= $this->isimg($ext);
		$strd= $str;
		if($lx==1)$str='href="javascript:;" onclick="return js.downshow('.$rs['id'].')"';
		if($lx>=2){
			$paths = $rs['filepath'];
			if(arrvalue($rs,'filepathout'))$paths = $rs['filepathout'];
			if(!$isimg)$paths='';
			$str='href="javascript:;" onclick="return c.downshow('.$rs['id'].',\''.$ext.'\',\''.$paths.'\',\''.$rs['filenum'].'\')"';//详情上预览
		}
		
		$flx   = $rs['fileext'];
		if(!$this->contain($this->fileall,','.$flx.','))$flx='wz';
		$str1  = '';
		$imurl = ''.URL.'web/images/fileicons/'.$flx.'.gif';
		$thumbpath = $this->getthumbpath($rs);
		if($isimg && !isempt($thumbpath))$imurl = $thumbpath;
		
		$isdel = file_exists($rs['filepath']);
		if(substr($rs['filepath'],0,4)=='http')$isdel=true;
		if(!isempt($rs['filenum']))$isdel=true;
		if(arrvalue($rs,'filepathout'))$isdel=true;
		
		$fstr .='<img src="'.$imurl.'" align="absmiddle" height=20 width=20>';
		if($isdel){
			$fstr .=' '.$rs['filename'].'';
		}else{
			$fstr .=' <s>'.$rs['filename'].'</s>';
		}
		
		$fstr .=' <span style="color:#aaaaaa;font-size:12px">('.$rs['filesizecn'].')</span>';
		
		$filenum = arrvalue($rs,'filenum');
		//if(!isempt($filenum)){
			$strd = 'href="javascript:;" onclick="js.fileopt('.$id.', 1)"';//下载的链接
		//}
		
		if($lx>=2){
			if($isdel){
				$fstr .= ' <a temp="clo" '.$strd.' class="blue">下载</a>';
				if($isimg || $this->isoffice($ext) || $this->isyulan($ext))
					$fstr .= '&nbsp; <a temp="clo" '.$str.' class="blue">预览</a>';
				if($this->isbianju($ext) && $lx==3)$fstr .='`'.$rs['id'].'`'; //用于编辑
			}else{
				$fstr .= ' <span style="color:#aaaaaa;font-size:12px">已删除</span>';
			}
		}

		return $fstr;
	}
	
	public function getfiles($mtype, $mid)
	{
		$rows		= $this->getall("`mtype`='$mtype' and `mid`='$mid' order by `id`");
		foreach($rows as $k=>$rs){
			$rows[$k]['status'] = 4;
		}
		return $rows;
	}
	
	public function getfilepath($mtype, $mid)
	{
		$rows		= $this->getfiles($mtype, $mid);
		$str 		= '';
		$nas 		= '';
		$st1		= '';
		foreach($rows as $k=>$rs){
			$path = $rs['filepath'];
			$outu = arrvalue($rs, 'filepathout');
			if(isempt($outu)){
				if(!isempt($path) && (file_exists($path) || substr($path,0,4)=='http') ){
					$str .= ','.$path.'';
					$nas .= ','.$rs['filename'].'';
				}
			}else{
				if($st1!='')$st1.='<br>';
				$st1.=''.$rs['filename'].'('.$rs['filesizecn'].')&nbsp;<a target="_blank" href="'.$outu.'">下载</a>';
			}
		}
		if($str!=''){
			$str = substr($str, 1);
			$nas = substr($nas, 1);
		}
		return array($str, $nas, $st1);
	}
	
	public function copyfile($mtype, $mid)
	{
		$rows	= $this->getall("`mtype`='$mtype' and `mid`='$mid' order by `id`");
		$arr 	= array();
		foreach($rows as $k=>$rs){
			$inuar  = $rs;
			if(isempt($rs['filepath']) || (substr($rs['filepath'],0,4)!='http' && !arrvalue($rs,'filepathout') && !file_exists($rs['filepath'])))continue;
			unset($inuar['id']);
			$oid	= $rs['id'];
			$inuar['adddt'] 	= $this->rock->now;
			$inuar['optid'] 	= $this->adminid;
			$inuar['optname'] 	= $this->adminname;
			$inuar['downci'] 	= '0';
			$inuar['mtype'] 	= '';
			$inuar['mid'] 		= '0';
			$inuar['oid'] 		= $oid;
			
			$ids 				= (int)$this->getmou('id','oid='.$oid.' and `mid`=0');
			if($ids==0){
				$this->insert($inuar);
				$inuar['id'] = $this->db->insert_id();
			}else{
				$inuar['id'] = $ids;
			}
			$inuar['status'] = 4;
			$arr[] = $inuar;
		}
		return $arr;
	}
	
	public function delfiles($mtype, $mid)
	{
		$where = "`mtype`='$mtype' and `mid`='$mid'";
		$this->delfile('', $where);
	}
	
	public function delfile($sid='', $where='')
	{
		if($sid!='')$where = "`id` in ($sid)";
		if($where=='')return;
		$rows 	= $this->getall($where);
		foreach($rows as $k=>$rs){
			$path = $rs['filepath'];
			if(!$this->isempt($path) && substr($path,0,4)!='http' && file_exists($path))unlink($path);
			$path = $rs['thumbpath'];
			if(!$this->isempt($path) && substr($path,0,4)!='http' && file_exists($path))unlink($path);
			$path = $rs['pdfpath'];
			if(!$this->isempt($path) && substr($path,0,4)!='http' && file_exists($path))unlink($path);
			
			if(!isempt($rs['filenum']))c('rockqueue')->push('flow,uptodelete', array('filenum'=>$rs['filenum']));//发送同步删除

		}
		$this->delete($where);
	}
	
	public function fileheader($filename,$ext='xls', $size=0)
	{
		$mime 		= $this->getmime($ext);
		$filename 	= $this->iconvutf8(str_replace(' ','',$filename));
		header('Content-type:'.$mime.'');
		header('Accept-Ranges: bytes');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		if($size>0)header('Content-Length:'.$size.'');
		header('Expires: 0');
		header('Content-disposition:attachment;filename='.$filename.'');
		header('Content-Transfer-Encoding: binary');
	}
	
	//渣渣IE才需要转化，真是醉了
	public function iconvutf8($text) {
		if(contain($this->rock->web,'IE')){
			return iconv('utf-8','gb2312', $text);
		}else{
			return $text;
		} 
    }
	
	public function show($id,$qx=false)
	{
		if($id==0)exit('Sorry!');
		$rs	= $this->getone($id);
		if(!$rs)exit('504 Not find files');
		if(!$qx && !$this->isdownfile($rs))exit('404 No permission download');
		$this->update("`downci`=`downci`+1", $id);
		$this->addlogs($id, 1);
		$filepath	= $rs['filepath'];
		$filename	= $rs['filename'];
		$filesize 	= $rs['filesize'];
		$fileext 	= $rs['fileext'];
		$filepathout= $rs['filepathout'];
		if($this->rock->contain($filepath,'http')){
			header('location:'.$filepath.'');
		}else{
			//2018-07-18只能下载upload/images下的文件
			$ielx  = substr($filepath,0,strlen(UPDIR));
			$ielx1 = substr($filepath,0,6);
			if($ielx!=UPDIR && $ielx1!='upload' && $ielx1!='images')exit('无效操作1');
			
			if(!file_exists($filepath)){
				if(!isempt($filepathout))header('location:'.$filepathout.'');
				exit('404 Not find files');
			}
			
			if(!contain($filename,'.'.$fileext.''))$filename .= '.'.$fileext.'';
			$filesize = filesize($filepath);
			$this->fileheader($filename, $fileext, $filesize);
			if(substr($filepath,-4)=='temp'){
				$content	= file_get_contents($filepath);
				echo base64_decode($content);
			}else{
				if($this->rock->iswebbro(0) && $this->rock->iswebbro(5)){
					header('location:'.$filepath.'');
					return;
				}
				ob_clean();flush();readfile($filepath);return;
				if($filesize > 5*1024*1024){
					header('location:'.$filepath.'');
				}else{
					echo file_get_contents($filepath);
				}
			}
		}
	}
	
	//这个是下载temp文件的
	public function download($id)
	{
		if($id==0)exit('Sorry!');
		$rs	= $this->getone($id);
		if(!$rs)exit('504 Not find files');
		if(!$this->isdownfile($rs))exit('No permission download');
		$filepath	= $rs['filepath'];
		$ielx  = substr($filepath,0,strlen(UPDIR));
		$ielx1 = substr($filepath,0,6);
		if($ielx!=UPDIR && $ielx1!='upload' && $ielx1!='images')exit('无效操作2');
		
		if(!file_exists($filepath))exit('404 Not find files');
		
		$this->update("`downci`=`downci`+1", $id);
		$this->addlogs($id, 1);
		
		$filename	= $rs['filename'];
		$filesize 	= $rs['filesize'];
		if(substr($filepath,-4)=='temp'){
			Header("Content-type: application/octet-stream");
			header('Accept-Ranges: bytes');
			Header("Accept-Length: ".$filesize);
			Header("Content-Length: ".$filesize);
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: no-cache');
			header('Expires: 0');
			$content	= file_get_contents($filepath);
			echo base64_decode($content);
		}else{
			
		}
	}
	
	//判断是否有下载文件的权限
	private function isdownfile($rs)
	{
		//return true; //如果不想权限判断就去掉注释直接返回true
		$uid = $this->adminid;
		if(arrvalue($rs,'optid')==$uid)return true;
		$table 	= arrvalue($rs,'mtype');
		$mid 	= (int)arrvalue($rs,'mid','0');
		if(!isempt($table) && $mid>0){
			$to = m('reads')->rows("`table`='$table' and `mid`='$mid' and `optid`='$uid'");
			if($to>0)return true;
		}
		$mknum = arrvalue($rs,'mknum');
		if(!isempt($mknum)){
			$mknuma = explode('|', $mknum);
			$num 	= $mknuma[0];
			$mid 	= (int)arrvalue($mknuma, 1, $mid);
			if($mid>0){
				$flow = m('flow')->initflow($num, $mid, false);
				if($flow->isreadqx(1))return true;
			}
		}
		if($table=='im_mess'){
			$ors = m($table)->getone($mid);
			if($ors){
				$receuid = $ors['receuid'];
				if(contain(','.$receuid.',',','.$uid.','))return true;
			}
		}
		if($table=='word'){
			$ors = m('word')->getone("`fileid`='".$rs['id']."'");
			if($ors){
				$cid = $ors['cid'];
				$flow = m('flow')->initflow('worc', $cid, false);
				if($flow->isreadqx(1))return true;
				$flow = m('flow')->initflow('word', $ors['id'], false);
				if($flow->isreadqx(1))return true;
			}
		}
		return false;
	}
}