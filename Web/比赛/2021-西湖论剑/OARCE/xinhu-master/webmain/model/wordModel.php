<?php
class wordClassModel extends Model
{
	private $optionobj;
	
	public function initModel()
	{
		$this->optionobj = m('option');
	}
	
	//获取路径
	public function getpath($id)
	{
		$this->pathss = array();
		$this->getpaths($id);
		return $this->pathss;
	}
	private function getpaths($id)
	{
		$rs = $this->getone($id);
		if($rs){
			$this->getpaths($rs['typeid']);
			$this->pathss[] = $rs;
		}
	}
	
	/**
	*	删除文件
	*/
	public function delword($id)
	{
		if($this->rows('`typeid`='.$id.'')>0)return returnerror('有子目录不能删除');
		$rs 	= $this->getone($id);
		$fid 	= arrvalue($rs,'fileid','0');
		$this->delete($id);
		m('file')->delfile($fid); //同时删除文件
		return returnsuccess();
	}
	
	
	
	/**
	*	创建文件夹,$cqid 分区ID, $typeid上级文件夹
	*/
	public function createfolder($name, $cqid, $typeid=0)
	{
		$arr['optid'] 	= $this->adminid;
		$arr['optname'] = $this->adminname;
		$arr['optdt'] 	= $this->rock->now;
		$arr['name'] 	= $name;
		$arr['cid'] 	= $cqid;
		$arr['typeid']  = $typeid;
		$arr['comid'] 	= m('admin')->getcompanyid();
		$arr['type'] 	= 1; //说明是文件夹
		$arr['id']		= $this->insert($arr);
		return $arr;
	}
	
	/**
	*	获取文档数据
	*	$lx=0文档中心,1所有共享,2我共享的
	*/
	public function getdata($lx=0)
	{
		//用来将原来的合并到现在的功能下
		$uid 	= $this->adminid;
		$fnum 	= 'folder'.$uid.'';
		$moopt	= m('option');
		$onrs 	= $moopt->getone("`num`='$fnum'");
		if($onrs && isempt($onrs['value'])){
			$alltypeid = $moopt->getalldownid($onrs['id']);
			$nto	   = $this->rows('cid=0 and `typeid` in('.$alltypeid.')');
			//存在的 建自己的分区
			if($nto>0){
				$adfe['name'] 		= ''.$this->adminname.'的分区';
				$adfe['recename'] 	= $this->adminname;
				$adfe['receid'] 	= 'u'.$uid.'';
				$adfe['guanname'] 	= $this->adminname;
				$adfe['guanid'] 	= 'u'.$uid.'';
				$adfe['optdt'] 		= $this->rock->now;
				$adfe['uid'] 		= $uid;
				$adfe['optname'] 	= $this->adminname;
				$mycid 	= m('worc')->insert($adfe);//新的分区
				$alltypeida = explode(',', $alltypeid);
				//建文件夹
				foreach($alltypeida as $ntyid){
					if($this->rows('typeid='.$ntyid.'')==0)continue;
					$fars = $moopt->getone($ntyid);
					
					$wjar = $this->createfolder($fars['name'], $mycid);//创建文件夹
					$ntypeid = $wjar['id'];
					$this->update(array('cid' => $mycid,'typeid' => $ntypeid),'`typeid`='.$ntyid.'');//更新
				}
			}
			$moopt->update("`value`='success'", $onrs['id']);
		}
		
		$cqids	= c('check')->onlynumber($this->rock->post('cqids','-1'));
		$cqid	= (int)$this->rock->post('cqid','0'); //打开的分区
		$typeid = (int)$this->rock->post('typeid','0'); //文件夹ID
		$atype  = $this->rock->post('atype');
		if($atype=='shateall')$lx=1;
		if($atype=='shatewfx')$lx=2;
		
		$ismanage = 0;
		$isup 	= 0;
		$key 	= $this->rock->post('key');
		$where 	= 'a.`cid` in('.$cqids.') ';
		
		
		//管理中心
		if($lx==0){
			if($cqid>0){
				$where.=' and a.`cid`='.$cqid.'';
				$dbs 	= m('admin');
				//判断是否有管理权限
				$cqrs  = m('worc')->getone($cqid);
				if(!isempt($cqrs['guanid'])){
					if($dbs->containjoin($cqrs['guanid'], $uid))$ismanage=1;
				}else{
					if($dbs->getmou('type', $uid)=='1')$ismanage=1;
				}
				
				if(isempt($cqrs['upuserid'])){
					if($cqrs['uid']==$uid)$isup = 1;
				}else{
					if($dbs->containjoin($cqrs['upuserid'], $uid))$isup=1;
				}
			}
			$where.=' and a.`typeid`='.$typeid.'';
			if(!isempt($key))$where = 'a.`cid` in('.$cqids.') ';
		}
		
		//共享给我的
		if($lx==1){
			$where1  = m('admin')->getjoinstrs('a.`shateid`', $uid, 1);
			//获取共享的目录ID
			$frows 	 = $this->db->getall('select a.id,a.cid from `[Q]word` a where a.`type`=1 '.$where1.'');
			$alltyids= '-1';
			foreach($frows as $k1=>$rs1){
				$afflow = $this->getfolders($rs1['cid'], $rs1['id']);
				$alltyids.=','.$rs1['id'].'';
				foreach($afflow as $k2=>$rs2)$alltyids.=','.$rs2['id'].'';
			}
			$where  = 'a.`type`=0 and ((1 '.$where1.') or (a.`typeid` in('.$alltyids.')))';
			$where .= m('admin')->getcompanywhere(1,'a.');
		}
		
		//我共享的
		if($lx==2){
			$where  = 'a.`type`=0 and a.`shateid` is not null and a.`optid`='.$uid.'';
			$where .= m('admin')->getcompanywhere(1,'a.');
		}
		
		//关键词的搜索
		if(!isempt($key)){
			$where .=" and (b.`filename` like '%$key%' or a.`name` like '%$key%')";
		}
		
		$sarr	= array(
			'where' => $where,
			'table' => '`[Q]word` a left join `[Q]file` b on a.`fileid`=b.`id`',
			'fields'=> 'a.*,b.filename,a.sort,b.filesizecn,b.filenum,b.thumbplat,b.filesize,b.fileext,b.filepath,b.thumbpath,b.downci,b.`filepathout`',
			'order' => 'a.`type` desc,a.`sort`,a.id desc'
		);
		
		$barr	= $this->getlimit($sarr['where'], (int)$this->rock->post('page','1'), $sarr['fields'], $sarr['order'], (int)$this->rock->post('limit','15'), $sarr['table']);
		
		$barr['totalCount'] = $barr['count'];
		$rows 	= $barr['rows'];
		$fobj	= m('file');
		
		//显示路径
		foreach($rows as $k=>&$rs){
			if($rs['type']=='1'){
				$rs['fileext'] 	= 'folder';
				$rs['optname'] 	= '';
				$rs['optdt'] 	= '';
				$rs['fileid'] 	= '';
				$downci = $this->rows('`typeid`='.$rs['id'].'');
				$rs['filesizecn'] 	= ($downci==0)?'':'<font color=#888888>有子目录</font>';
				
			}else{
				if(isempt($rs['name']))$rs['name'] = $rs['filename'];
				$rs['thumbpath'] = $fobj->getthumbpath($rs); //缩略图的路径
				
				$fpath = $rs['filepath'];
				$wjstatus= 1;
				if(substr($fpath,0,4)=='http'){
					$wjstatus = 2;
				}else{
					$filepathout = $rs['filepathout'];
					if(isempt($filepathout)){
						if(!file_exists($fpath) && isempt($rs['filenum'])){
							$wjstatus=0;
							$rs['ishui']=1;
						}
					}else{
						if($fobj->isimg($rs['fileext']))$rs['filepath'] = $filepathout;
						$wjstatus = 2;
					}
				}
				$rs['wjstatus'] = 2;
			}
		}
		if($lx==0){
			$barr['cprow']= m('worc')->getone($cqid);
			$barr['patha']= $this->getpath($typeid);
		}
		$barr['rows'] = $rows;
		$barr['ismanage'] = $ismanage; //是否有管理权限
		$barr['isup'] 	  = $isup; //是否可上传
		$barr['officebj'] =  getconfig('officebj');
		return $barr;
	}
	
	/**
	*	保存文件
	*/
	public function savefile()
	{
		$cid 	= (int)$this->rock->post('cqid',0);
		$sid 	= $this->rock->post('sid');
		$typeid = (int)$this->rock->post('typeid','0');
		$sadid	= explode(',', $sid);
		
		$arr['optid'] 	= $this->adminid;
		$arr['optname'] = $this->adminname;
		$arr['optdt'] 	= $this->rock->now;
		$arr['cid'] 	= $cid;
		$arr['comid'] 	= m('admin')->getcompanyid();
		$arr['typeid'] 	= $typeid;
		$file 			= m('file');
		$id				= 0;
		foreach($sadid as $fid){
			$arr['fileid'] = $fid;
			$id = $this->insert($arr);
			$file->addfile($fid, 'word', $id, 'word');
		}
		$names = '';
		$frows = $file->getall('`id` in('.$sid.')');
		$zongs = count($frows);
		foreach($frows as $k=>$rs){
			if($k<3)$names.=','.$rs['filename'].'';
		}
		
		//发送推送通知
		$cprs = m('worc')->getone($cid);
		$fors = $this->getone($typeid);
		if($cprs && $names!=''){
			$names= substr($names, 1);
			$receid= $cprs['receid'];
			if(isempt($receid))$receid = 'u0';
			if(!isempt(arrvalue($fors,'shateid')))$receid.=','.$fors['shateid'].''; //同时发给共享的
			
			$cont = "{$this->adminname}在“{$cprs['name']}”上传了{$zongs}个文件“{$names}”";
			$flow = m('flow')->initflow('word');
			if(arrvalue($flow->moders,'pctx')=='1')$flow->push($receid,'文档', $cont, ''.$this->adminname.'上传了文件',0, array(
				'wxurl' => $flow->getwxurl(),
				'id' => $id
			));
		}
	}
	
	/**
	*	获取分区文件夹
	*/
	public function getworcfolder()
	{
		$barr = m('worc')->getmywroc();
		$ids  = $barr['ids'];
		$rows = $barr['rows'];
		
		
		$arr  = array();
		foreach($rows as $k=>$rs){
			if($rs['ismanage']==0)continue;
			$cqid  = $rs['id']; //区id
			$arr[] = array(
				'cqid' 	=> $cqid,
				'typeid'=> 0,
				'iconsimg'=> 'images/wjj.png',
				'iconswidth'=> '24',
				'name' 	=> $rs['name'],
			);
			
			$rowa  = $this->getfolders($rs['id']);
			foreach($rowa as $k1=>$rs1){
				$rs1['cqid'] 	 = $cqid;
				$rs1['iconsimg'] = 'images/folder.png';
				$arr[] = $rs1;
			}
		}
		return $arr;
	}
		
	//获取文件夹,获取对应子目录
	public function getfolders($cid, $typeid=0)
	{
		$rows = $this->getall('`cid`='.$cid.' and `type`=1','`id`,`name`,`typeid`','`sort`');
		$this->getfoldersa = array();
		$this->getfolderss($rows, $typeid,1);
		return $this->getfoldersa;
	}
	private function getfolderss($rows, $typeid, $lev=1)
	{
		foreach($rows as $k=>$rs){
			if($rs['typeid']==$typeid){
				$rs['padding'] = 24*$lev;
				$rs['typeid']  = $rs['id'];
				$this->getfoldersa[] = $rs;
				$this->getfolderss($rows, $rs['id'], $lev+1);
			}
		}
	}
	
	
	
	
	/**
	*	移动
	*/
	public function movefile()
	{
		$cqid 	= (int)$this->rock->post('cqid','0');
		$typeid = (int)$this->rock->post('typeid','0');//文件夹
		$ids 	= $this->rock->post('ids','0');//要移动文件
		if($typeid>0){
			//判断是否在自己文件夹下
			$foldpath = $this->getpath($typeid);
			$foldar   = array();
			foreach($foldpath as $k1=>$rs1)$foldar[] = $rs1['id'];
			$idsa 	  = explode(',', $ids);
			foreach($idsa as $ids1){
				if(in_array($ids1, $foldar))return returnerror('['.$ids1.']不能移动到自己的子目录下');
			}
		}
		
		$this->update('`cid`='.$cqid.',`typeid`='.$typeid.'', "`id` in($ids)");
		
		//获取所有下级需要更新分区Id
		$this->moveaddid 	= '0';
		$this->getmovedanow($ids);
		if($this->moveaddid!='0')$this->update('`cid`='.$cqid.'', '`id` in('.$this->moveaddid.')');
		
		return returnsuccess();
	}
	private function getmovedanow($ids)
	{
		$addid 	= '';
		$rows 	= $this->getall('`typeid` in('.$ids.')');
		foreach($rows as $k=>$rs){
			$addid.=','.$rs['id'].'';
		}
		if($addid!=''){
			$addid = substr($addid,1);
			$this->moveaddid.=','.$addid.'';
			$this->getmovedanow($addid);
		}
	}
	
	/**
	*	共享
	*/
	public function sharefile()
	{
		$ids 			= $this->rock->post('ids','0');
		$arr['shateid'] = $this->rock->post('sid');
		$arr['shate']   = $this->rock->post('sna');
		$this->update($arr, "`id` in($ids)");
		
		
		//发通知给对应人说共享了
		if(!isempt($arr['shateid'])){
			$names= '';
			$rows = $this->db->getall('select a.name,b.filename,a.id from `[Q]word` a left join `[Q]file` b on a.fileid=b.id where a.`id` in('.$ids.')');
			$id   = 0;
			foreach($rows as $k=>$rs){
				$nas = $rs['name'];
				if(isempt($nas))$nas = $rs['filename'];
				if(!isempt($nas))$names.=','.$nas.'';
				if($id==0)$id = $rs['id'];
			}
			if($names!=''){
				$names= substr($names, 1);
				$cont = "{$this->adminname}共享了文件“{$names}”";
				$flow = m('flow')->initflow('word');
				$flow->push($arr['shateid'],'文档', $cont, ''.$this->adminname.'发来共享文件',0, array(
					'wxurl' => $flow->getwxurl(),
					'id'	=> $id
				));
			}
		}
	}
	
	
	
	
	//----------------以下是旧的，后续会删除-------------
	
	/**
	*	读取对应顶级ＩＤ
	*/
	public function getfolderid($uid, $isdept=false)
	{
		$num = "folder".$uid."";
		$name= ''.$this->adminname.'文件夹目录';
		if($isdept){
			$drs = m('dept')->getudept($uid);
			$num = 'deptfolder'.$drs['nums'].'';
			$name= ''.$drs['name'].'文件夹目录';
		}
		$id  = $this->optionobj->getnumtoid($num, $name, false);
		return $id;
	}
	
	/**
	*	对应对应文件目录
	*/
	public function getfoldrows($uid, $isdept=false)
	{
		$pid 	= $this->getfolderid($uid, $isdept);
		$rows 	= $this->optionobj->gettreedata($pid);
		return $rows;
	}
	
	/**
	*	读取所有目录
	*/
	private $allfolder = array();
	public function getallfolder($idss='',$level=0)
	{
		$where = "`num` like 'folder%' or `num` like 'deptfolder%'";
		if(!isempt($idss))$where = "`pid` in($idss)";
		$rows = $this->db->getall("SELECT * FROM `[Q]option` where $where");
		$ids  = '';
		foreach($rows as $k=>$rs){
			$ids.=','.$rs['id'].'';
			$rs['level'] = $level;
			if(!isempt($idss))$this->allfolder[] = $rs;
		}
		if($ids!=''){
			$this->getallfolder(substr($ids, 1), $level+1);
		}
		return $this->allfolder;
	}
	
	/**
	*	获取分享给我的目录(太复杂了，无法进行了)
	*/
	public function getshatefolder($uid)
	{
		$allfolder 	= $this->getallfolder();
		$urs 		= $this->db->getone('[Q]admin','`id`='.$uid.'','id,deptpath');
		$uarr[]		= 'u'.$uid.'';
		$deptpath 	= arrvalue($urs, 'deptpath');
		if(!isempt($deptpath)){
			$depa = explode(',', str_replace(array('[',']'), array('',''), $deptpath));
			foreach($depa as $depas){
				$uarr[] = 'd'.$depas.'';
			}
		}
		$rows 		= $this->getshatefolders($allfolder, 1, $uarr, 0);
		return $rows;
	}
	private function getshatefolders($allfolder, $level, $uarr, $pid=0)
	{
		$rarr = array();
		foreach($allfolder as $k=>$rs){
			$receid = $rs['receid'];
			if($level==1 && $rs['level']!=$level)continue;
			if($level>1 && $pid!=$rs['pid'])continue;
			$bo 	 = false;
			if(!isempt($receid)){
				$receida = explode(',', $receid);
				foreach($uarr as $uarrs){
					if(in_array($uarrs, $receida))$bo=true;
				}
			}

			$rs['children'] = $this->getshatefolders($allfolder, $level+1, $uarr, $rs['id']);
			$rarr[] = $rs;
		}
		return $rarr;
	}
	
	/**
	*	模版替换
	*/
	public function replaceWord($fid, $arr)
	{
		$word  = c('PHPWord');
		if($fid==0 || !$word->isbool())return;
		$frs = m('file')->getone($fid);
		if($frs && $frs['fileext']=='docx'){
			$filepath = $frs['filepath'];
			if(contain($filepath,'rocktpl')){
				$tplid = str_replace('.docx','',substr($filepath, strripos($filepath, '_')+1));
				$tplrs = m('wordxie')->getone('`fileid`='.$tplid.'');
				$npath = ''.UPDIR.'/'.date('Y-m').'/'.date('d_His').''.rand(10,99).'.docx';
				if(!$tplrs)return;
				
				$this->rock->createdir($npath);
				$tplvar = $tplrs['tplvar'];
				$tihad	= array();
				if(isempt($tplvar)){
					$tihad = $arr;
				}else{
					$tpla = explode(',', $tplvar);
					foreach($tpla as $k)$tihad[$k] = arrvalue($arr, $k);
				}
			
				if($tihad){
					$barr = $word->replaceWord($filepath, $tihad, $npath);
					if($barr['success']){
						$nnpath = $barr['data'];
						if(file_exists($nnpath)){
							$filesize = filesize($nnpath);
							$uarr['filepathout']= '';
							$uarr['pdfpath'] 	= '';
							$uarr['filepath'] 	= $nnpath;
							$uarr['filesize'] 	= $filesize;
							$uarr['filesizecn'] = $this->rock->formatsize($filesize);	
							m('file')->update($uarr,$fid);
							@unlink($filepath);
						}
					}
				}
			}
		}
	}
}