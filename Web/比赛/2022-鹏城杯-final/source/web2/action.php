<?php
/*
$accepted_origins = array("https://wx.a.com", "http://test.b.wang");
if (isset($_SERVER['HTTP_ORIGIN'])) {
  // 验证来源是否在白名单内
  if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
  } else {
    die('{"status":-99,"msg":"无权限"}');
  }
}*/
error_reporting(0);  


include_once("config.php");
include_once("PasswordStorage.php");
if(!isset($_REQUEST['mode'])){
	$result = '{"status":0}';
}else{
	$mode=$_REQUEST['mode'];
	
	if($mode=='login'){//后台登录
		//session_start();
		$u=$_POST['user'];
		$p=$_POST['pass'];
		$_SESSION['user']=$u;
		//$p=PasswordStorage::create_hash($p);
		$blackpattern="/\*|'|\"|#|;|,|or|\^|=|<|>|and|update|union|delete|from|select|insert|create|drop|alter/i";
		if(preg_match($blackpattern, $u))
			{   rlog('提交信息包含敏感词汇！');
				die('{"status":0,"msg":"提交信息包含敏感词汇！"}');
		    }
		$sql="select password,status,juese,bm from user where username='$u'";
		$requ=mysqli_query($con,$sql);
		if($requ){
			$rs=mysqli_fetch_array($requ);
			if($rs){
				if($rs['status']==1){
					$r=PasswordStorage::verify_password($p, $rs['password']);
					if($r){
						$result = '{"status":1}';
						$_SESSION['admin']=$u;
						$_SESSION['juese']=$rs['juese'];
						$_SESSION['bm'] = $rs['bm'];
						rlog('登录成功');
						//var_dump($_SESSION);
					}else{
						$result = '{"status":0,"msg":"密码错误"}';
						rlog('登录失败，密码错误');
					}
				}else{
					$result = '{"status":0,"msg":"用户被禁用"}';
					rlog('登录失败，被禁用');
				}
			}else{
				$result = '{"status":0,"msg":"用户名错误"}';
				rlog('登录失败，用户名错误');
			}
		}else{
			$result = '{"status":0,"msg":"系统错误"}';
			rlog('登录失败');
		}
	}
	if($mode=='logout'){//后台退出
		rlog('退出成功');
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		session_unset();
		session_destroy();
		header("location:/page/login.php");
		die();
	}
	if($mode=='changepassword'){//修改密码
		//session_start();
		//$o=$_POST['oldp'];
		//$p=$_POST['newp'];
		extract($_POST);
		print_r($oldp);
		$u=$_SESSION['admin'];
		//var_dump($_SESSION['admin']);
		$sql="select username,password from user where username='$u'";
		$requ=mysqli_query($con,$sql);
		$rs=mysqli_fetch_array($requ);

		$r=PasswordStorage::verify_password($oldp, $rs['password']);

		if($r){
			$p=PasswordStorage::create_hash($newp);
			$sql="update user set password='$p' where username='".$rs['username']."'";
			mysqli_query($con,$sql);
			if(mysqli_affected_rows($con)){
				$result = '{"status":1}';
				rlog('修改密码成功');
			}else{
				$result = '{"status":0,"msg":"密码修改失败，请重试"}';
				rlog('修改密码失败');
			}
		}else{
			$result = '{"status":0,"msg":"原密码错误"}';
			rlog('修改密码失败，原密码错误');
		}
	}
	if($mode=='getzichanleixing'){//获取资产类型列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		if(isset($_GET['dw'])){
			$dw = ' and zcfl='.$_GET['dw'];
		}else{
			$dw = '';
		}
		$sqls="select id,name,zcfl,status from zclx where 1=1 $dw";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$zcfz=array("","信息中心","办公室","物管办");
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['name'].'",
					   "zcfz":"'.$zcfz[$rs['zcfl']].'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog('获取资产类型列表');
	}
	if($mode=='chengezclxzt'){//更改资产类型状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update zclx set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("ok!更改资产类型状态$id");
		}else{
			$result = '{"status":0}';
			rlog("no!更改资产类型状态$id");
		}
	}
	if($mode=='addzclx'){//添加资产类型
		$name=$_POST['name'];
		$sql = "select id from zclx where name='$name'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result = '{"status":0,"msg":"名称已存在"}';
			rlog("no!添加资产类型，重复。$name");
		}else{
			$zcfz = $_POST['zcfz'];
			$sql = "insert into zclx (name, zcfl, status) values ('$name', $zcfz, 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("OK！添加资产类型$name");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("NO！添加资产类型$name");
			}
		}
	}
	if($mode=='getzichanzhuangtai'){//获取资产状态列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select id,name,status from zhuangtai where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['name'].'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取资产状态列表");
	}
	if($mode=='chengezcztzt'){//更改资产状态的状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update zhuangtai set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("OK！更改资产状态$id");
		}else{
			$result = '{"status":0}';
			rlog("no！更改资产状态$id");
		}
	}
	if($mode=='addzczt'){//添加资产状态
		$name=$_POST['name'];
		$sql = "select id from zhuangtai where name='$name'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result = '{"status":0,"msg":"名称已存在"}';
			rlog("no！添加资产状态$name");
		}else{
			$sql = "insert into zhuangtai (name, status) values ('$name', 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("ok！添加资产状态$name");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("no！添加资产状态$name");
			}
		}
	}
	if($mode=='getdanweilist'){//获取单位列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select id,name,status from danwei where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['name'].'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取单位列表");
	}
	if($mode=='chengedwzt'){//更改单位状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update danwei set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("OK！ 更改单位状态$id");
		}else{
			$result = '{"status":0}';
			rlog("NO！ 更改单位状态$id");
		}
	}
	if($mode=='adddanwei'){//添加单位
		$name=$_POST['name'];
		$sql = "select id from danwei where name='$name'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result = '{"status":0,"msg":"名称已存在"}';
			rlog("NO！添加单位重复$name");
		}else{
			$sql = "insert into danwei (name, status) values ('$name', 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("OK！添加单位$name");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("NO！ 添加单位失败$name");
			}
		}
	}
	if($mode=='getjueselist'){//获取角色列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select a.id as id,a.name as name,a.status as status,
			   a.shanchu as shanchu,a.xiugai as xiugai,
			   a.value as value,danwei.name as bm 
			   from juese as a 
			   left join danwei on a.bm=danwei.id
			   where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$v = $rs['value'];
			$bm = $rs['bm'];
			if(empty($bm)){$bm = '全局';}
			$v = rtrim($v,',');
			$sqq = "select title from system_menu where id in ($v)";
			//echo $sqq;
			$req = mysqli_query($con,$sqq);
			$s = '';
			while($rss = mysqli_fetch_array($req)){
				$s.=$rss['title'].',';
			}
			$s = rtrim($s,',');
			$result.='{"id":'.$rs['id'].',"qx":"'.$s.'","name":"'.$rs['name'].'",
					   "shanchu":'.$rs['shanchu'].',"xiugai":'.$rs['xiugai'].',
					   "qxbm":"'.$bm.'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取角色列表");
	}
	if($mode=='chengejszt'){//更改角色状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update juese set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("更改角色$id 状态ok");
		}else{
			$result = '{"status":0}';
			rlog("更改角色$id 状态no");
		}
	}
	if($mode=='chengejssc'){//更改角色删除权限
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update juese set shanchu=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("更改角色$id 状态ok");
		}else{
			$result = '{"status":0}';
			rlog("更改角色$id 状态no");
		}
	}
	if($mode=='chengejsxg'){//更改角色修改权限
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update juese set xiugai=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("更改角色$id 状态ok");
		}else{
			$result = '{"status":0}';
			rlog("更改角色$id 状态no");
		}
	}
	if($mode=='addjuese'){//添加角色
		$name=$_POST['name'];
		$v = $_POST['value'];
		$qxbm = $_POST['qxbm'];
		$sql = "select id from juese where name='$name'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result = '{"status":0,"msg":"名称已存在"}';
		}else{
			$sql = "insert into juese (name, value, bm, status) values ('$name', '$v', $qxbm, 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("添加角色 $name 成功,权限 $v ");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("添加角色 $name 失败");
			}
		}
	}
	if($mode == 'editjuese'){//编辑角色权限
		$id = $_REQUEST['id'];
		$v = $_POST['value'];
		$u = $_POST['u'];
		$qxbm = $_POST['qxbm'];
		$sql = "update juese set name='$u',value='$v',bm=$qxbm where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("编辑角色 $id 权限 $v ok ");
		}else{
			$result = '{"status":0,"msg":"未修改或修改失败"}';
			rlog("编辑角色 $id 权限 $v no ");
		}
	}
	if($mode=='getuserlist'){//用户列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select a.id as id,a.username as username,a.juese as juese,
			   a.status as status,danwei.name as bm
			  from user as a 
			  left join danwei on a.bm=danwei.id
			  where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$ssq = "select name from juese where id=".$rs['juese'];
			$requu=mysqli_query($con,$ssq);
			$rsr=mysqli_fetch_array($requu);
			$bm = $rs['bm'];
			if(empty($bm)){$bm='全局';}
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['username'].'","qxbm":"'.$bm.'",
					   "juese":"'.$rsr['name'].'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog('get用户列表');
	}
	if($mode == 'addyonghu'){//添加用户
		$name = $_POST['name'];
		$sql = "select id from user where username='$name'";
		$requ=mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result='{"status":"0","msg":"用户名已存在"}';
		}else{
			$js = $_POST['js'];
			$bm = $_POST['qxbm'];
			$sql = "insert into user (username, password, juese, bm, status) 
					values ('$name', 'sha512:10000:24:hXJefLjmwWFX4gcbuo3+/gHyJoAV8FFd:/JDiHC7RyWfjJljsshZZvKcx1KJFoCK+', $js, $bm, 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("添加用户成功 $name");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("添加用户失败 $name");
			}
		}
	}
	if($mode=='chengeyhzt'){//更改用户状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update user set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("更改用户 $id 状态 $zhi OK ");
		}else{
			$result = '{"status":0}';
			rlog("更改用户 $id 状态 $zhi no ");
		}
	}
	if($mode == 'chengeuserpassword'){//重置用户密码
		if ($_SESSION['admin']!='admin')
			{die('请登录管理员进行重置密码！！');}
		$id=$_POST['id'];
		$sql="update user set password='sha512:10000:24:0b3SCgEF931of0dHfNWHlZeqqlaqqZfR:Dqy9jZcoSgXDvfxs6jZJkUL3ZcFhhG+r' where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("重置用户 $id 密码 OK ");
		}else{
			$result = '{"status":0}';
			rlog("重置用户 $id 密码 no ");
		}
	}
	if($mode == 'edityonghu'){//编辑用户
		$id=$_POST['id'];
		$n=$_POST['name'];
		$js=$_POST['js'];
		$bm = $_POST['qxbm'];
		$sql="update user set username='$n',juese=$js,bm=$bm where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("编辑用户 $id $n $js 成功");
		}else{
			$result = '{"status":0,"msg":"修改失败或未做修改"}';
			rlog("编辑用户 $id $n $js 失败");
		}
	}
	if($mode=='addwgbgszc'){//添加物管办和办公室资产
		$data=$_POST['data'];
		$ll='[{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'",
			   "act":"新增","new":'.$data.'}]';
		$d=json_decode($data);
		$zz=$_POST['zz'];
		$sjk = $zz==2?'bgszichan':'wgbzichan';
		$zcbh=$d->zcbh;
		$xlh=$d->xlh;
		if(!empty($zcbh)){
			$sql = "select zcbh from $sjk where zcbh='$zcbh'";
			$requ = mysqli_query($con,$sql);
			if(mysqli_num_rows($requ)){
				die('{"status":"0","msg":"资产编号重复"}');
			}
		}
		$sql = "select xlh from $sjk where xlh='$xlh'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			die('{"status":"0","msg":"序列号重复"}');
		}
		$zclx=$d->zclx;
		$zczt=$d->zczt;
		$bm=$d->bm;
		$bgr=$d->bgr;
		$dz=$d->dz;
		$cgsj=strtotime($d->cgsj);
		$rzsj=strtotime($d->rzsj);
		$zbsc=$d->zbsc;
		$sysc=$d->sysc;
		$pp=$d->pp;
		$xh=$d->xh;
		$zcly=$d->zcly;
		$zcjz=$d->zcjz;
		$gg=$d->gg;
		$bz=$d->bz;
		$img=$d->img;
		$sql = "insert into $sjk (zcbh,xlh,zclx,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,sysc,pp,xh,zcly,zcjz,bz,img,gg,ll) values 
								 ('$zcbh','$xlh',$zclx,$zczt,$bm,'$bgr','$dz',$cgsj,$rzsj,$zbsc,$sysc,'$pp','$xh','$zcly',$zcjz,'$bz','$img','$gg','$ll')";
		//echo $sql;
		mysqli_query($con,$sql);
		if(mysqli_insert_id($con)){
			$result='{"status":"1"}';
			rlog("添加 $sjk 资产 成功");
		}else{
			$result='{"status":"0","msg":"添加失败，请重试"}';
			rlog("添加 $sjk 资产 失败");
		}
		$_SESSION['addlishi']=$data;
	}
	if($mode =='daoru'){//导入
		$zz = $_POST['zz'];
		$q = $_POST['q'];
		$z = $_POST['z'];
		$f = $_POST['f'];
		$f = "upfile/$f";
		$sjk=array('','xinxizichan','bgszichan','wgbzichan');
		$sjk=$sjk[$zz];
		$bmz = $_SESSION['bm'];//角色权限部门ID 

		require_once './Excel/PHPExcel/IOFactory.php';
		$objPHPExcel = PHPExcel_IOFactory::load($f);
		$objWorksheet = $objPHPExcel->getSheet(0); 
		//$objWorksheet = $objPHPExcel->getActiveSheet();
		if($z == '' || $z < 1){
			$z = $objWorksheet->getHighestRow(); //总行数
		}
		for($row=$q;$row<=$z;$row++){
			$zcbh=$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
			$xlh=$objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
			$zclx=$objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
			$zczt=$objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
			$bm=$objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
			if($bmz != 0){
			if($bm != $bmz){
				echo $row.'无权限<br>';
				continue;
			}}
			$bgr=$objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
			$dz=$objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
			$cgsj=$objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
			$cgsj=date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($cgsj));
			$rzsj=$objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
			$rzsj=date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($rzsj));
			$zbsc=$objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
			$sysc=$objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
			$pp=$objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
			$xh=$objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
			$zcly=$objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
			$zcjz=$objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
			$gg=$objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
			$bz=$objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
			$img=$objWorksheet->getCellByColumnAndRow(17, $row)->getValue();

			$wlbs=$objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
			if(empty($wlbs)){$wlbs=0;}
			$ip=$objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
			$xsq=$objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
			$yp=$objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
			if(empty($yp)){$yp=0;}
			$nc=$objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
			if(empty($nc)){$nc=0;}
				
			$sql = "select xlh from $sjk where xlh='$xlh'";
			$requ = mysqli_query($con,$sql);
			if(mysqli_num_rows($requ)){
				echo $row . "：序列号重复<br>";
			}else{
				if(!empty($zcbh)){
					$sql = "select zcbh from $sjk where zcbh='$zcbh'";
					$requ = mysqli_query($con,$sql);
					if(mysqli_num_rows($requ)){
						$bhcf = true;
					}else{
						$bhcf = false;
					}
				}else{
					$bhcf = false;
				}
				if($bhcf){
					echo $row . "：资产编号重复<br>";
				}else{
					if($zz == 1){
						$ll = '[{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'",
								"act":"导入新增","new":{"zclx":"'.$zclx.'","zczt":"'.$zczt.'","zcbh":"'.$zcbh.'",
								"xlh":"'.$xlh.'","bgr":"'.$bgr.'","bm":"'.$bm.'","dz":"'.$dz.'","cgsj":"'.$cgsj.'",
								"rzsj":"'.$rzsj.'","zbsc":"'.$zbsc.'","sysc":"'.$sysc.'","pp":"'.$pp.'","xh":"'.$xh.'",
								"gg":"'.$gg.'","zcly":"'.$zcly.'","zcjz":"'.$zcjz.'","bz":"'.$bz.'","file":"",
								"wlbs":"'.$wlbs.'","ip":"'.$ip.'","yp":"'.$yp.'","xsq":"'.$xsq.'",
								"nc":"'.$nc.'","img":"'.$img.'"}}]';
						$cgsj=strtotime($cgsj);
						$rzsj=strtotime($rzsj);
						$sql = "insert into $sjk (zcbh,xlh,zclx,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,sysc,pp,xh,zcly,zcjz,bz,img,gg,ll,wlbs,ip,xsq,yp,nc) values 
												 ('$zcbh','$xlh',$zclx,$zczt,$bm,'$bgr','$dz',$cgsj,$rzsj,$zbsc,$sysc,'$pp','$xh','$zcly',$zcjz,'$bz','$img','$gg','$ll',$wlbs,'$ip','$xsq',$yp,$nc)";
					}else{
						$ll = '[{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'",
								"act":"导入新增","new":{"zclx":"'.$zclx.'","zczt":"'.$zczt.'","zcbh":"'.$zcbh.'",
								"xlh":"'.$xlh.'","bgr":"'.$bgr.'","bm":"'.$bm.'","dz":"'.$dz.'","cgsj":"'.$cgsj.'",
								"rzsj":"'.$rzsj.'","zbsc":"'.$zbsc.'","sysc":"'.$sysc.'","pp":"'.$pp.'","xh":"'.$xh.'",
								"gg":"'.$gg.'","zcly":"'.$zcly.'","zcjz":"'.$zcjz.'","bz":"'.$bz.'","file":"","img":"'.$img.'"}}]';
						$cgsj=strtotime($cgsj);
						$rzsj=strtotime($rzsj);
						$sql = "insert into $sjk (zcbh,xlh,zclx,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,sysc,pp,xh,zcly,zcjz,bz,img,gg,ll) values 
												 ('$zcbh','$xlh',$zclx,$zczt,$bm,'$bgr','$dz',$cgsj,$rzsj,$zbsc,$sysc,'$pp','$xh','$zcly',$zcjz,'$bz','$img','$gg','$ll')";
					}
					mysqli_query($con,$sql);
					if(mysqli_insert_id($con)){
						echo $row . "：导入成功<br>";
					}else{
						echo $row . "：导入失败<br> $sql <br>";
					}
				}
			}
		}
		$result='';
		rlog("导入 $f ");
	}
	if($mode=='addxxzxzc'){//添加信息中心资产
		$data=$_POST['data'];
		$ll='[{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'",
			   "act":"新增","new":'.$data.'}]';
		$d=json_decode($data);
		//$zz=$_POST['zz'];
		//$sjk = $zz==2?'bgszichan':'wgbzichan';
		$sjk = 'xinxizichan';
		$zcbh=$d->zcbh;
		$xlh=$d->xlh;
		if(!empty($zcbh)){
			$sql = "select zcbh from $sjk where zcbh='$zcbh'";
			$requ = mysqli_query($con,$sql);
			if(mysqli_num_rows($requ)){
				die('{"status":"0","msg":"资产编号重复"}');
			}
		}
		$sql = "select xlh from $sjk where xlh='$xlh'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			die('{"status":"0","msg":"序列号重复"}');
		}
		$zclx=$d->zclx;
		$zczt=$d->zczt;
		$bm=$d->bm;
		$bgr=$d->bgr;
		$dz=$d->dz;
		$cgsj=strtotime($d->cgsj);
		$rzsj=strtotime($d->rzsj);
		$zbsc=$d->zbsc;
		$sysc=$d->sysc;
		$pp=$d->pp;
		$xh=$d->xh;
		$zcly=$d->zcly;
		$zcjz=$d->zcjz;
		$gg=$d->gg;
		$bz=$d->bz;
		$img=$d->img;
		$wlbs=$d->wlbs;
		$ip=$d->ip;
		$xsq=$d->xsq;
		$yp=$d->yp;
		$nc=$d->nc;
		if(empty($wlbs)){$wlbs=0;}
		if(empty($yp)){$yp=0;}
		if(empty($nc)){$nc=0;}
		$sql = "insert into $sjk (zcbh,xlh,zclx,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,sysc,pp,xh,zcly,zcjz,bz,img,gg,ll,wlbs,ip,xsq,yp,nc) values 
								 ('$zcbh','$xlh',$zclx,$zczt,$bm,'$bgr','$dz',$cgsj,$rzsj,$zbsc,$sysc,'$pp','$xh','$zcly',$zcjz,'$bz','$img','$gg','$ll',$wlbs,'$ip','$xsq',$yp,$nc)";

		mysqli_query($con,$sql);
		if(mysqli_insert_id($con)){
			$result='{"status":"1"}';
			rlog("添加 $sjk 资产ok");
		}else{
			$result='{"status":"0","msg":"添加失败，请重试"}';
			rlog("添加 $sjk 资产no");
		}
		$_SESSION['addlishi']=$data;
	}
	if($mode=='searchzichan'){//资产查询
		$dw=$_GET['dw'];//部门代码
		$sjk=array('','xinxizichan','bgszichan','wgbzichan');
		$sjk=$sjk[$dw];
		$bmz = $_SESSION['bm'];//角色权限部门ID 
		if($bmz == 0){
			$dwa='';
			$dwz = '';
		}else{
			$dwa=" and id=$bmz";
			$dwz = " and a.bm=$bmz";
		}
		if(isset($_POST['columns'])){//获取筛选数据
			$res='{';
			$col = urldecode($_POST['columns']);
			header('content-type:application/json,charset=UTF-8');
			$sql = "select name from zclx where status=1 and zcfl=$dw";
			$requ=mysqli_query($con,$sql);
			$res.='"zclx":[';
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"zczt":[';
			$sql = "select name from zhuangtai where status=1";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"bm":[';
			$sql = "select name from danwei where status=1 $dwa";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"pp":[';
			$sql = "select DISTINCT pp from $sjk where 1=1";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['pp'].'",';
			}
			$res=rtrim($res,',');
			$res.=']}';
			die($res);
		}else{
			//获取资产数据
			if(isset($_REQUEST['filterSos'])){//筛选
				$shaixuan = json_decode(urldecode($_REQUEST['filterSos']));
				$xx='';
				foreach ($shaixuan as $item) {
					$v=$item->values;
					if(empty($v)){
						continue;
					}
					$sj = $item->field;
					if($sj == 'pp'){
						$str = '';
						foreach($v as $a){
							$str .= "'$a',";
						}
						$str = rtrim($str,',');
						$xx.=" and a.pp in ($str)";
					}else{
						if($sj=='zclx'){$sjkk='zclx';}
						if($sj=='zczt'){$sjkk='zhuangtai';}
						if($sj=='bm'){$sjkk='danwei';}
						$d = '';
						foreach($v as $a){
							$sql = "select id from $sjkk where name='$a'";
							//echo 'sql:'.$sql.'<br>';
							$requ = mysqli_query($con,$sql);
							$rs = mysqli_fetch_array($requ);
							$d.=$rs['id'].',';
						}
						$d=rtrim($d,',');
						$xx.=" and a.$sj in ($d)";
					}
				}
				//echo $xx.'<br>';
			}else{
				$xx = '';
			}


			$p=$_GET['page'];
			$l=$_GET['limit'];
			$p=($p-1)*$l;
			
			if(isset($_GET['rzqi'])){
				$rzqi = $_GET['rzqi'];
				$rzzhi = $_GET['rzzhi'];
				$mhss = $_GET['mhss'];
				if($rzqi == ''){
					$qi = 0;
				}else{
					$qi = strtotime($rzqi);
				}
				if($rzzhi == ''){
					$zi = 9999999999;
				}else{
					$zi = strtotime($rzzhi);
				}
				$shijian = " and a.rzsj between $qi and $zi";
				if(empty($mhss)){
					$mhss='';
				}else{
					$mhss = " and (a.zcbh like '%$mhss%' 
								or a.xlh like '%$mhss%' 
								or a.bgr like '%$mhss%'
								or a.dz like '%$mhss%'
								or a.pp like '%$mhss%'
								or a.xh like '%$mhss%'
								or a.gg like '%$mhss%'
								or a.bz like '%$mhss%'
								or a.ip like '%$mhss%'
								or a.zcly like '%$mhss%')";
				}
			}else{
				$shijian = '';
				$mhss = '';
			}
			
			if($dw == 1){
				$sqls="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.wlbs as wlbs,a.ip as ip,a.yp as yp,
							  a.xsq as xsq,a.nc as nc,a.img as img,
							  zhuangtai.name as zczt,
							  danwei.name as bm,zclx.name as zclx 
							  from $sjk as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $xx $shijian $mhss $dwz";
			}else{
				$sqls="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.img as img,danwei.name as bm,
							  zclx.name as zclx,zhuangtai.name as zczt 
							  from `$sjk` as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $xx $dwz";
			}		  
			$sql=$sqls." limit $p,$l";
			$requ=mysqli_query($con,$sqls);
			if (!$requ) {
				echo $sqls.'<br>';
				printf("Error: %s\n", mysqli_error($con));
				exit();
			}
			$num=mysqli_num_rows($requ);
			$requ=mysqli_query($con,$sql);
			$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
			while($rs=mysqli_fetch_array($requ)){
				$cgsj = date("Y-m-d",$rs['cgsj']);
				$rzsj = date("Y-m-d",$rs['rzsj']);
				$result.='{"id":"'.$rs['id'].'","zcbh":"'.$rs['zcbh'].'","xlh":"'.$rs['xlh'].'",
						   "zclx":"'.$rs['zclx'].'","zczt":"'.$rs['zczt'].'",
						   "bm":"'.$rs['bm'].'","bgr":"'.$rs['bgr'].'","dz":"'.$rs['dz'].'",
						   "cgsj":"'.$cgsj.'","rzsj":"'.$rzsj.'",
						   "zbsc":"'.$rs['zbsc'].'","sysc":"'.$rs['sysc'].'",
						   "pp":"'.$rs['pp'].'","xh":"'.$rs['xh'].'","zcly":"'.$rs['zcly'].'",
						   "zcjz":"'.$rs['zcjz'].'","gg":"'.$rs['gg'].'",
						   "bz":"'.$rs['bz'].'","img":"'.$rs['img'].'"';
				if($dw == 1){
					$w=array("未指定","内网","外网");
					$result.=',"wlbs":"'.$w[$rs['wlbs']].'","ip":"'.$rs['ip'].'",
							  "xsq":"'.$rs['xsq'].'","yp":"'.$rs['yp'].'","nc":"'.$rs['nc'].'"},';
				}else{
					$result.='},';
				}
			}
			$result=rtrim($result,',');
			$result.=']}';
		}
		rlog("获取 $sjk  资产列表");
	}
	if($mode=='xiugaixxzxzc'){//修改信息中心资产
		$sjk = 'xinxizichan';
		$id=$_POST['id'];
		$sql = "select ll from $sjk where id=$id";
		$r = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($r);
		$ll = $rs['ll'];//该资产原履历
		$ll=rtrim($ll,']');
		//var_dump($rs['ll']);
		//var_dump(json_decode($rs['ll'])[0]->new->img);
		$data=$_POST['data'];
		$d=json_decode($data);
		$new = '{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'","act":"修改","new":'.$data.'}';
		$ll.=",$new]";//新履历
		$zcbh=$d->zcbh;
		$xlh=$d->xlh;
		$zclx=$d->zclx;
		$zczt=$d->zczt;
		$bm=$d->bm;
		$bgr=$d->bgr;
		$dz=$d->dz;
		$cgsj=strtotime($d->cgsj);
		$rzsj=strtotime($d->rzsj);
		$zbsc=$d->zbsc;
		$sysc=$d->sysc;
		$pp=$d->pp;
		$xh=$d->xh;
		$zcly=$d->zcly;
		$zcjz=$d->zcjz;
		$gg=$d->gg;
		$bz=$d->bz;
		$img=$d->img;
		$wlbs=$d->wlbs;
		$ip=$d->ip;
		$xsq=$d->xsq;
		$yp=$d->yp;
		$nc=$d->nc;
		if(empty($wlbs)){$wlbs=0;}
		if(empty($yp)){$yp=0;}
		if(empty($nc)){$nc=0;}
		$sql = "update $sjk set zcbh='$zcbh',xlh='$xlh',zclx=$zclx,zczt=$zczt,
				bm=$bm,bgr='$bgr',dz='$dz',cgsj=$cgsj,rzsj=$rzsj,zbsc=$zbsc,sysc=$sysc,
				pp='$pp',xh='$xh',zcly='$zcly',zcjz=$zcjz,bz='$bz',img='$img',gg='$gg',
				wlbs=$wlbs,ip='$ip',xsq='$xsq',yp=$yp,nc=$nc,ll='$ll' where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			rename('.'.json_decode($rs['ll'])[0]->new->img, '.'.$img);
			$result='{"status":"1"}';
			rlog("修改 $sjk 资产 $id 成功 ");
		}else{
			$result='{"status":"0","msg":"修改失败，请重试"}';
			rlog("修改 $sjk 资产 $id  no ");
		}
	}
	if($mode == 'xiugaizc'){//修改办公室、物管办资产
		$id = $_POST['id'];
		$sjk = $_POST['sjk'];
		$sql = "select ll from $sjk where id=$id";
		$r = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($r);
		$ll = $rs['ll'];//该资产原履历
		$ll=rtrim($ll,']');
		$data=$_POST['data'];
		$d=json_decode($data);
		$new = '{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'","act":"修改","new":'.$data.'}';
		$ll.=",$new]";//新履历
		$zcbh=$d->zcbh;
		$xlh=$d->xlh;
		$zclx=$d->zclx;
		$zczt=$d->zczt;
		$bm=$d->bm;
		$bgr=$d->bgr;
		$dz=$d->dz;
		$cgsj=strtotime($d->cgsj);
		$rzsj=strtotime($d->rzsj);
		$zbsc=$d->zbsc;
		$sysc=$d->sysc;
		$pp=$d->pp;
		$xh=$d->xh;
		$zcly=$d->zcly;
		$zcjz=$d->zcjz;
		$gg=$d->gg;
		$bz=$d->bz;
		$img=$d->img;
		$sql = "update $sjk set zcbh='$zcbh',xlh='$xlh',zclx=$zclx,zczt=$zczt,
				bm=$bm,bgr='$bgr',dz='$dz',cgsj=$cgsj,rzsj=$rzsj,zbsc=$zbsc,sysc=$sysc,
				pp='$pp',xh='$xh',zcly='$zcly',zcjz=$zcjz,bz='$bz',img='$img',gg='$gg',
				ll='$ll' where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result='{"status":"1"}';
			rlog("修改 $sjk 资产 $id 成功 ");
		}else{
			$result='{"status":"0","msg":"修改失败，请重试"}';
			rlog("修改 $sjk 资产 $id no ");
		}
	}
	if($mode == 'downloadzclist'){//资产导出
			$sjk = $_GET['zz'];
			$bmz = $_SESSION['bm'];//角色权限部门ID 
			if($bmz == 0){
				$bmz = '';
			}else{
				$bmz = " and a.bm=$bmz";
			}
			rlog("导出 $sjk 资产");
			if($sjk == 'xinxizichan'){
				$f = 'xxzx.xlsx';
				$sql="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.wlbs as wlbs,a.ip as ip,a.yp as yp,
							  a.xsq as xsq,a.nc as nc,a.img as img,
							  zhuangtai.name as zczt,
							  danwei.name as bm,zclx.name as zclx 
							  from $sjk as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $bmz";
			}else{
				$f = 'wgbgs.xlsx';
				$sql="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.img as img,danwei.name as bm,
							  zclx.name as zclx,zhuangtai.name as zczt 
							  from `$sjk` as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $bmz";
			}
			require_once './Excel/PHPExcel/IOFactory.php';
			require_once './Excel/PHPExcel.php';
			$reader = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $reader->load($f);
			$sheet = $excel->getSheet(0);
			$row=2;
			$requ=mysqli_query($con,$sql);
			if (!$requ) {
				printf("Error: %s\n", mysqli_error($con));
				exit();
			}

			while($rs=mysqli_fetch_array($requ)){
				$excel->getActiveSheet()->getRowDimension($row)->setRowHeight(22);//设置行高
				$excel->getActiveSheet(0)->setCellValue('A'.$row, $rs['zclx']);
				$excel->getActiveSheet(0)->setCellValue('B'.$row, $rs['zczt']);
				$excel->getActiveSheet(0)->setCellValue('C'.$row, $rs['zcbh']);
				$excel->getActiveSheet(0)->setCellValue('D'.$row, $rs['xlh']);
				$excel->getActiveSheet(0)->setCellValue('E'.$row, $rs['bgr']);
				$excel->getActiveSheet(0)->setCellValue('F'.$row, $rs['bm']);
				$excel->getActiveSheet(0)->setCellValue('G'.$row, $rs['dz']);
				$excel->getActiveSheet(0)->setCellValue('H'.$row, $rs['pp']);
				$excel->getActiveSheet(0)->setCellValue('I'.$row, $rs['xh']);
				$excel->getActiveSheet(0)->setCellValue('J'.$row, $rs['gg']);
				$excel->getActiveSheet(0)->setCellValue('K'.$row, date("Y-m-d",$rs['cgsj']));
				$excel->getActiveSheet(0)->setCellValue('L'.$row, date("Y-m-d",$rs['rzsj']));
				$excel->getActiveSheet(0)->setCellValue('M'.$row, $rs['zbsc']);
				$excel->getActiveSheet(0)->setCellValue('N'.$row, $rs['sysc']);
				$excel->getActiveSheet(0)->setCellValue('O'.$row, $rs['zcly']);
				$excel->getActiveSheet(0)->setCellValue('P'.$row, $rs['zcjz']);
				if($sjk == 'xinxizichan'){
					$wlbs = array("未指定","内网","外网");
					$wlbs = $wlbs[$rs['wlbs']];
					$excel->getActiveSheet(0)->setCellValue('Q'.$row, $wlbs);
					$excel->getActiveSheet(0)->setCellValue('R'.$row, $rs['ip']);
					$excel->getActiveSheet(0)->setCellValue('S'.$row, $rs['xsq']);
					$excel->getActiveSheet(0)->setCellValue('T'.$row, $rs['yp']);
					$excel->getActiveSheet(0)->setCellValue('U'.$row, $rs['nc']);
					$excel->getActiveSheet(0)->setCellValue('W'.$row, $rs['bz']);
					$img = $rs['img'];
					if(!empty($img)){
						$excel->setActiveSheetIndex(0)->getStyle('V')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet(0)->getColumnDimension('V')->setWidth(10);
						if(substr($img,0,7) != 'http://'){
							$img = substr($img,1);
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setPath($img);
							$objDrawing->setWidth(30);
							$objDrawing->setHeight(22);
							$objDrawing->setCoordinates('V'.$row);
							$objDrawing->setOffsetX(0);
							$objDrawing->setOffsetY(0);
							$objDrawing->setWorksheet($excel->getActiveSheet());
							$img = $url.$img;
							$excel->getActiveSheet()->getCell('V'.$row)->getHyperlink()->setUrl($img);
						}else{
							$excel->getActiveSheet()->setCellValue('V'.$row, $img);
							$excel->getActiveSheet()->getCell('V'.$row)->getHyperlink()->setUrl($img);
						}
					}
				}else{
					$excel->getActiveSheet(0)->setCellValue('R'.$row, $rs['bz']);
					$img = $rs['img'];
					if(!empty($img)){
						$excel->setActiveSheetIndex(0)->getStyle('Q')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet(0)->getColumnDimension('Q')->setWidth(10);
						if(substr($img,0,7) != 'http://'){
							$img = substr($img,1);
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setPath($img);
							$objDrawing->setWidth(30);
							$objDrawing->setHeight(22);
							$objDrawing->setCoordinates('Q'.$row);
							$objDrawing->setOffsetX(0);
							$objDrawing->setOffsetY(0);
							$objDrawing->setWorksheet($excel->getActiveSheet());
							$img = $url.$img;
							$excel->getActiveSheet()->getCell('Q'.$row)->getHyperlink()->setUrl($img);
						}else{
							$excel->getActiveSheet()->setCellValue('Q'.$row, $img);
							$excel->getActiveSheet()->getCell('Q'.$row)->getHyperlink()->setUrl($img);
						}
					}
				}
				$row++;
			}

			$sheet->getDefaultRowDimension()->setRowHeight(22);//设置默认行高
			
			$file=$sjk.'_'.date("Y-m-d").'.xlsx';
			$objWrite = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');  
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器数据excel07文件
			header("Content-Type: application/force-download"); 
			header("Content-Type: application/octet-stream"); 
			header("Content-Type: application/download"); 
			header("Content-Disposition:attachment;filename=$file");  
			header("Pragma: no-cache"); 
			header('Cache-Control: max-age=0');//禁止缓存
			$objWrite->save('php://output');
			exit;
	}
	if($mode == 'getrizhi'){//获取操作日志
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select * from log where 1=1";
		$sql=$sqls." limit $p,$l";
		mysqli_query($con, 'SET NAMES UTF8');
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"user":"'.$rs['user'].'",
						"action":"'.$rs['action'].'",
						"ip":"'.$rs['ip'].'","time":"'.$rs['time'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取日志");
	}
	if($mode == 'xgcs'){//修改参数
		$sjk = $_POST['sjk'];
		$v = $_POST['v'];
		$sql = "update config set value=$v where title='$sjk'";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":"1"}';
		}else{
			$result = '{"status":"0"}';
		}
		rlog("修改参数");
	}
	if($mode == 'deletezichan'){//删除资产
		$sjk = $_POST['sjk'];
		$id = $_POST['id'];
		$sql = "delete from $sjk where id in ($id)";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":"1"}';
			rlog("删除资产 $id 成功");
		}else{
			$result = '{"status":"0"}';
			rlog("删除资产 $id 失败");
		}
	}
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($mode=='searchzichandemo'){//资产查询---测试
		$dw=$_GET['dw'];//部门代码
		$sjk=array('','xinxizichan','bgszichan','wgbzichan');
		$sjk=$sjk[$dw];
		$bmz = $_SESSION['bm'];//角色权限部门ID 
		if($bmz == 0){
			$dwa='';
			$dwz = '';
		}else{
			$dwa=" and id=$bmz";
			$dwz = " and a.bm=$bmz";
		}
		if(isset($_POST['columns'])){//获取筛选数据
			$res='{';
			$col = urldecode($_POST['columns']);
			header('content-type:application/json,charset=UTF-8');
			$sql = "select name from zclx where status=1 and zcfl=$dw";
			$requ=mysqli_query($con,$sql);
			$res.='"zclx":[';
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"zczt":[';
			$sql = "select name from zhuangtai where status=1";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"bm":[';
			$sql = "select name from danwei where status=1 $dwa";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"pp":[';
			$sql = "select DISTINCT pp from $sjk where 1=1";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['pp'].'",';
			}
			$res=rtrim($res,',');
			$res.=']}';
			die($res);
		}else{
			//获取资产数据
			if(isset($_REQUEST['filterSos'])){//筛选
				$shaixuan = json_decode(urldecode($_REQUEST['filterSos']));
				$xx='';
				foreach ($shaixuan as $item) {
					$v=$item->values;
					if(empty($v)){
						continue;
					}
					$sj = $item->field;
					if($sj == 'pp'){
						$str = '';
						foreach($v as $a){
							$str .= "'$a',";
						}
						$str = rtrim($str,',');
						$xx.=" and a.pp in ($str)";
					}else{
						if($sj=='zclx'){$sjkk='zclx';}
						if($sj=='zczt'){$sjkk='zhuangtai';}
						if($sj=='bm'){$sjkk='danwei';}
						$d = '';
						foreach($v as $a){
							$sql = "select id from $sjkk where name='$a'";
							//echo 'sql:'.$sql.'<br>';
							$requ = mysqli_query($con,$sql);
							$rs = mysqli_fetch_array($requ);
							$d.=$rs['id'].',';
						}
						$d=rtrim($d,',');
						$xx.=" and a.$sj in ($d)";
					}
				}
				//echo $xx.'<br>';
			}else{
				$xx = '';
			}


			$p=$_GET['page'];
			$l=$_GET['limit'];
			$p=($p-1)*$l;
			
			if(isset($_GET['rzqi'])){
				$rzqi = $_GET['rzqi'];
				$rzzhi = $_GET['rzzhi'];
				$mhss = $_GET['mhss'];
				if($rzqi == ''){
					$qi = 0;
				}else{
					$qi = strtotime($rzqi);
				}
				if($rzzhi == ''){
					$zi = 9999999999;
				}else{
					$zi = strtotime($rzzhi);
				}
				$shijian = " and a.rzsj between $qi and $zi";
				if(empty($mhss)){
					$mhss='';
				}else{
					$mhss = " and (a.zcbh like '%$mhss%' 
								or a.xlh like '%$mhss%' 
								or a.bgr like '%$mhss%'
								or a.dz like '%$mhss%'
								or a.pp like '%$mhss%'
								or a.xh like '%$mhss%'
								or a.gg like '%$mhss%'
								or a.bz like '%$mhss%'
								or a.ip like '%$mhss%'
								or a.zcly like '%$mhss%')";
				}
			}else{
				$shijian = '';
				$mhss = '';
			}
			
			if($dw == 1){
				$sqls="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.wlbs as wlbs,a.ip as ip,a.yp as yp,
							  a.xsq as xsq,a.nc as nc,a.img as img,
							  a.zclx as zclxid,a.zczt as zcztid,
							  zhuangtai.name as zczt,a.bm as bmid,
							  danwei.name as bm,zclx.name as zclx 
							  from $sjk as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $xx $shijian $mhss $dwz";
			}else{
				$sqls="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.img as img,danwei.name as bm,
							  a.bm as bmid,a.zclx as zclxid,a.zczt as zcztid,
							  zclx.name as zclx,zhuangtai.name as zczt 
							  from `$sjk` as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $xx $dwz";
			}		  
			$sql=$sqls." limit $p,$l";
			$requ=mysqli_query($con,$sqls);
			if (!$requ) {
				echo $sqls.'<br>';
				printf("Error: %s\n", mysqli_error($con));
				exit();
			}
			$num=mysqli_num_rows($requ);
			$requ=mysqli_query($con,$sql);
			$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
			while($rs=mysqli_fetch_array($requ)){
				$cgsj = date("Y-m-d",$rs['cgsj']);
				$rzsj = date("Y-m-d",$rs['rzsj']);
				$result.='{"id":"'.$rs['id'].'","zcbh":"'.$rs['zcbh'].'","xlh":"'.$rs['xlh'].'",
						   "zclx":{"name":'.$rs['zclxid'].',"value":"'.$rs['zclx'].'"},
						   "zczt":{"name":'.$rs['zcztid'].',"value":"'.$rs['zczt'].'"},
						   "bm":{"name":'.$rs['bmid'].',"value":"'.$rs['bm'].'"},
						   "bgr":"'.$rs['bgr'].'","dz":"'.$rs['dz'].'",
						   "cgsj":"'.$cgsj.'","rzsj":"'.$rzsj.'",
						   "zbsc":"'.$rs['zbsc'].'","sysc":"'.$rs['sysc'].'",
						   "pp":"'.$rs['pp'].'","xh":"'.$rs['xh'].'","zcly":"'.$rs['zcly'].'",
						   "zcjz":"'.$rs['zcjz'].'","gg":"'.$rs['gg'].'",
						   "bz":"'.$rs['bz'].'","img":"'.$rs['img'].'"';
				if($dw == 1){
					$w=array("未指定","内网","外网");
					$result.=',"wlbs":{"name":'.$rs['wlbs'].',"value":"'.$w[$rs['wlbs']].'"},
							   "ip":"'.$rs['ip'].'","xsq":"'.$rs['xsq'].'",
							   "yp":"'.$rs['yp'].'","nc":"'.$rs['nc'].'"},';
				}else{
					$result.='},';
				}
			}
			$result=rtrim($result,',');
			$result.=']}';
		}
		rlog("获取 $sjk  资产列表");
	}
	
	

	echo $result;
}	

function rlog($a){
	//session_start();
	global $con;
	$u=$_SESSION['user'];
	$ip = getCilentIP();
	$sql = "insert into log (user,action,ip) values ('$u','$a','$ip')";
	mysqli_query($con,$sql);
}	

function getCilentIP(){
	$ip = '';
	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_FROM', 'REMOTE_ADDR') as $v) {
		if (isset($_SERVER[$v])) {
			if (! preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $_SERVER[$v])) {
				continue;
			} 
			 $ip = $_SERVER[$v];
		}
	}
	return $ip;
}	
