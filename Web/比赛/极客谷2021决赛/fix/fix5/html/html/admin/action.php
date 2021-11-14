<?php

include 'head.php';

$act = $_GET["act"];

//上传增加数据库

if($act == "save_uplod"){

 if($_FILES['file']['size']>0 && $_FILES['file']['name']!="")

 {

	    $file_size_max    = 3072000; //3000k

		$store_dir        = "../upload/";

		$ext_arr          = array('csv','xls','txt');

		$accept_overwrite = true;

		$date1            = date("YmdHis");

		$file_type        = extend($_FILES['file']['name']);

		$newname          = $date1.".".$file_type;

		//判断格式		

		if (in_array($file_type,$ext_arr) === false){

		  echo "<script>alert('上传的文件格式错误，请按要求的文件格式上传');history.back()</script>";

		  exit;

	   }

	    //判断文件的大小

		if ($_FILES['file']['size'] > $file_size_max) {

		  echo "<script>alert('对不起，你上传的文件大于3000k');history.back()</script>";

		  exit;

		}

		

		if (file_exists($store_dir.$_FILES['file']['name'])&&!$accept_overwrite)

		{

		  echo "<script>alert('文件已存在，不能新建');history.back()</script>";

		  exit;

		}

		if (!move_uploaded_file($_FILES['file']['tmp_name'],$store_dir.$newname)) {

		  echo "<script>alert('复制文件失败');history.back()</script>";

		  exit;

		}

	  $filepath = $store_dir.$newname;

	  

	 }else{

	   $filepath = "";

	   

	 }

	 if($filepath == ""){



	    echo "<script>alert('请先选择要上传的文件');history.back()</script>";

		exit;

	 }

	

	$file_encoding = $_POST["file_encoding"];

    

	if($file_type == "xls"){

	  // 创建 Reader.

	  $data = new Spreadsheet_Excel_Reader();

	  // 设置文本输出编码.

	  $data->setOutputEncoding('utf-8');

	  //读取Excel文件.

	  $data->read($filepath);

	  //error_reporting(E_ALL ^ E_NOTICE);

	  //$data->sheets[0]['numCols']为Excel列数

	  for ($i = 2; $i < $data->sheets[0]['numRows']; $i++) {

		 //判断上传的是否有重复

		$sql = "select id from tgs_agent where agentid='".$data->sheets[0]['cells'][$i][1]."' limit 1";	

		$res = mysql_query($sql);	

		$arr = mysql_fetch_array($res);	

		if(mysql_num_rows($res)>0){

		echo "<script>alert('该代理商编号已存在，请修改！');location.href='agent.php?act=edit&id=".$arr["id"]."'</script>";

		exit;

		}

		$sql = "INSERT INTO tgs_agent (agentid,name,idcard,weixin,phone,qq,quyu,dengji,sjdl,addtime,jietime,beizhu,shzt,hmd,password)VALUES('".

		// //显示每个单元格内容

		$data->sheets[0]['cells'][$i][1]."','".

		$data->sheets[0]['cells'][$i][2]."','".

		$data->sheets[0]['cells'][$i][3]."','".

		$data->sheets[0]['cells'][$i][4]."','".

		$data->sheets[0]['cells'][$i][5]."','".

		$data->sheets[0]['cells'][$i][6]."','".

		$data->sheets[0]['cells'][$i][7]."','".

		$data->sheets[0]['cells'][$i][8]."','".

		$data->sheets[0]['cells'][$i][9]."','".

		$data->sheets[0]['cells'][$i][10]."','".

		$data->sheets[0]['cells'][$i][11]."','".
	
		$data->sheets[0]['cells'][$i][12]."','1','2','12345678')";

		mysql_query($sql);

	}	  

	$k=$i-2;



    ////导入csv文件///////////////////////////

	}elseif($file_type == "csv"){	   

	  setlocale(LC_ALL, 'zh_CN.UTF-8');

	   $file  = fopen($filepath,"r");  

	   $k     = 1;

	   while(!feof($file) && $data = __fgetcsv($file))

	   {

		 $result = array();  

		   if($k>1 && !empty($data))

		   {  

			  for($i=0;$i<22;$i++)

			  {

				  array_push($result,$data[$i]);

			  }			  

		      if($file_encoding == "gbk"){			   

			   $result_1 = iconv("gbk", "utf-8"."//IGNORE", $result[1]);

			   $result_2 = iconv("gbk", "utf-8"."//IGNORE", $result[2]);

			   $result_3 = iconv("gbk", "utf-8"."//IGNORE", $result[3]);

			   $result_4 = iconv("gbk", "utf-8"."//IGNORE", $result[4]);

			   $result_5 = iconv("gbk", "utf-8"."//IGNORE", $result[5]);

			   $result_6 = iconv("gbk", "utf-8"."//IGNORE", $result[6]);

			   $result_7 = iconv("gbk", "utf-8"."//IGNORE", $result[7]);

			   $result_8 = iconv("gbk", "utf-8"."//IGNORE", $result[8]);

			   $result_9 = iconv("gbk", "utf-8"."//IGNORE", $result[9]);

			   $result_10 = iconv("gbk", "utf-8"."//IGNORE", $result[10]);

			   $result_11 = iconv("gbk", "utf-8"."//IGNORE", $result[11]);

			   $result_12 = iconv("gbk", "utf-8"."//IGNORE", $result[12]);

			   $result_13 = iconv("gbk", "utf-8"."//IGNORE", $result[13]);

			   $result_14 = iconv("gbk", "utf-8"."//IGNORE", $result[14]);

			   $result_15 = iconv("gbk", "utf-8"."//IGNORE", $result[15]);

			   $result_16 = iconv("gbk", "utf-8"."//IGNORE", $result[16]);

			   $result_17 = iconv("gbk", "utf-8"."//IGNORE", $result[17]);

			   $result_18 = iconv("gbk", "utf-8"."//IGNORE", $result[18]);

			   $result_19 = iconv("gbk", "utf-8"."//IGNORE", $result[19]);

			   $result_20 = iconv("gbk", "utf-8"."//IGNORE", $result[20]);

			   $result_21 = iconv("gbk", "utf-8"."//IGNORE", $result[21]);			  

			  }else{			  

			   $result_1 = $result[1];

			   $result_2 = $result[2];

			   $result_3 = $result[3];

			   $result_4 = $result[4];

			   $result_5 = $result[5];

			   $result_6 = $result[6];

			   $result_7 = $result[7];

			   $result_8 = $result[8];

			   $result_9 = $result[9];

			   $result_10 = $result[10];

			   $result_11 = $result[11];

			   $result_12 = $result[12];

			   $result_13 = $result[13];

			   $result_14 = $result[14];

			   $result_15 = $result[15];

			   $result_16 = $result[16];

			   $result_17 = $result[17];

			   $result_18 = $result[18];

			   $result_19 = $result[19];

			   $result_20 = $result[20];

			   $result_21 = $result[21];

			  }  			  

			  //判断上传的是否有重复

			$sql = "select id from tgs_agent where agentid='".$result[0]."' limit 1";	

			$res = mysql_query($sql);	

			$arr = mysql_fetch_array($res);	

			if(mysql_num_rows($res)>0){

			echo "<script>alert('该代理商编号已存在，请修改！');location.href='agent.php?act=edit&id=".$arr["id"]."'</script>";

			exit;

			}else{

			//$sql = "insert into tgs_agent (bianhao,riqi,product,zd1,zd2) values ('".$result[0]."','".$result_1."','".$result_2."','".$result_3."','".$result_4."')";

			$sql = "insert into tgs_agent (agentid,name,idcard,weixin,phone,qq,quyu,dengji,sjdl,addtime,jietime,beizhu,shzt,hmd,password) values ('".$result[0]."','".$result_1."','".$result_2."','".$result_3."','".$result_4."','".$result_5."','".$result_6."','".$result_7."','".$result_8."','".$result_9."','".$result_10."','".$result_11."','1','2','12345678')";

			mysql_query($sql) or die("ERR:".$sql);

			}

		  }  

		 $k++;  

		 }

		 $k=$k-2;

		 fclose($file);

		 

    ///导入txt文件//////////////////////////////

	}elseif($file_type == "txt"){	    

		$row = file($filepath); //读出文件中内容到一个数组当中

		$k   = 1;//统计表中的记录数

		for ($i=1;$i<count($row);$i++)//开始导入记录 

		{ 

			$result = explode(",",$row[$i]);//读取数据到数组中，以英文逗号为分格符

			if($file_encoding == "gbk"){			   

			   $result_1 = iconv("gbk", "utf-8"."//IGNORE", $result[1]);

			   $result_2 = iconv("gbk", "utf-8"."//IGNORE", $result[2]);

			   $result_3 = iconv("gbk", "utf-8"."//IGNORE", $result[3]);

			   $result_4 = iconv("gbk", "utf-8"."//IGNORE", $result[4]);

			   $result_5 = iconv("gbk", "utf-8"."//IGNORE", $result[5]);

			   $result_6 = iconv("gbk", "utf-8"."//IGNORE", $result[6]);

			   $result_7 = iconv("gbk", "utf-8"."//IGNORE", $result[7]);

			   $result_8 = iconv("gbk", "utf-8"."//IGNORE", $result[8]);

			   $result_9 = iconv("gbk", "utf-8"."//IGNORE", $result[9]);

			   $result_10 = iconv("gbk", "utf-8"."//IGNORE", $result[10]);

			   $result_11 = iconv("gbk", "utf-8"."//IGNORE", $result[11]);

			   $result_12 = iconv("gbk", "utf-8"."//IGNORE", $result[12]);

			   $result_13 = iconv("gbk", "utf-8"."//IGNORE", $result[13]);

			   $result_14 = iconv("gbk", "utf-8"."//IGNORE", $result[14]);

			   $result_15 = iconv("gbk", "utf-8"."//IGNORE", $result[15]);

			   $result_16 = iconv("gbk", "utf-8"."//IGNORE", $result[16]);

			   $result_17 = iconv("gbk", "utf-8"."//IGNORE", $result[17]);

			   $result_18 = iconv("gbk", "utf-8"."//IGNORE", $result[18]);

			   $result_19 = iconv("gbk", "utf-8"."//IGNORE", $result[19]);

			   $result_20 = iconv("gbk", "utf-8"."//IGNORE", $result[20]);

			   $result_21 = iconv("gbk", "utf-8"."//IGNORE", $result[21]);			  

		    }else{			  

			   $result_1 = $result[1];

			   $result_2 = $result[2];

			   $result_3 = $result[3];

			   $result_4 = $result[4];

			   $result_5 = $result[5];

			   $result_6 = $result[6];

			   $result_7 = $result[7];

			   $result_8 = $result[8];

			   $result_9 = $result[9];

			   $result_10 = $result[10];

			   $result_11 = $result[11];

			   $result_12 = $result[12];

			   $result_13 = $result[13];

			   $result_14 = $result[14];

			   $result_15 = $result[15];

			   $result_16 = $result[16];

			   $result_17 = $result[17];

			   $result_18 = $result[18];

			   $result_19 = $result[19];

			   $result_20 = $result[20];

			   $result_21 = $result[21];

		    }  

			//当只有防伪码时，下面的数值跟随保存到数据库

			//if($result_1 ==""){

//				$result_1="2020-12-30";

//			}

//			if($result_2 ==""){

//				$result_2="产品名称";

//			}

//			if($result_3 ==""){

//				$result_3="备用A";

//			}

//			if($result_4 ==""){

//				$result_4="备用B";

//			}

			//判断上传的是否有重复

			$sql = "select id from tgs_agent where agentid='".$result[0]."' limit 1";	

			$res = mysql_query($sql);	

			$arr = mysql_fetch_array($res);	

			if(mysql_num_rows($res)>0){

			echo "<script>alert('该代理商编号已存在，请修改！');location.href='agent.php?act=edit&id=".$arr["id"]."'</script>";

			exit;

			}else{

			$sql = "insert into tgs_agent (agentid,name,idcard,weixin,phone,qq,quyu,dengji,sjdl,addtime,jietime,beizhu,shzt,hmd,password) values ('".$result[0]."','".$result_1."','".$result_2."','".$result_3."','".$result_4."','".$result_5."','".$result_6."','".$result_7."','".$result_8."','".$result_9."','".$result_10."','".$result_11."','1','2','12345678')";

			mysql_query($sql);

			$k++;

			}

		}

		$k=$k-1;

		fclose($row);

	}

	$msg= "上传成功".$k."条记录";

	@unlink($filepath);

	echo "<script>alert('".$msg."');location.href='agent.php?'</script>";

	exit;

}





////添加代理信息

if($act == "Addagent")

{   

    $agentid      = trim($_REQUEST["agentid"]);
	 $idcard      = trim($_REQUEST["idcard"]);
	 	 $dengji      = trim($_REQUEST["dengji"]);

	$product      = trim($_REQUEST["product"]);

	$quyu         = trim($_REQUEST["quyu"]);
	$applytime         = trim($_REQUEST["applytime"]);

	$shuyu        = trim($_REQUEST["shuyu"]);

	$url          = strreplace(trim($_REQUEST["url"]));

	$qudao        = trim($_REQUEST["qudao"]);

	$about        = strreplace(trim($_REQUEST["about"]));

	$addtime      = trim($_REQUEST["addtime"]);

	$jietime      = trim($_REQUEST["jietime"]);

	$name         = trim($_REQUEST["name"]);

	$tel          = trim($_REQUEST["tel"]);

	$fax          = trim($_REQUEST["fax"]);

	$phone        = trim($_REQUEST["phone"]);

	$danwei       = trim($_REQUEST["danwei"]);

	$email        = trim($_REQUEST["email"]);	

	$qq           = trim($_REQUEST["qq"]);

	$weixin       = trim($_REQUEST["weixin"]);

	$wangwang     = trim($_REQUEST["wangwang"]);

	$paipai       = trim($_REQUEST["paipai"]);
	
	$sjdl       = trim($_REQUEST["sjdl"]);
	
	$shzt       = trim($_REQUEST["shzt"]);
	
	$hmd       = trim($_REQUEST["hmd"]);
	$password       = trim($_REQUEST["password"]);

	$zip          = trim($_REQUEST["zip"]);

	$dizhi        = strreplace(trim($_REQUEST["dizhi"]));

	$beizhu       = strreplace(trim($_REQUEST["beizhu"]));	
	$sql="select * from tgs_agent where weixin='$sjdl' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	$sjdlid      = $arr["id"];
	if(empty($sjdl)){
}
else{

	if($sjdlid=="")

	{

	  echo "<script>alert('上级代理微信号不存在，请检查');history.go(-1);</script>";

	  exit;

	}
}

	if($agentid=="")

	{

	  echo "<script>alert('代理商编号不能为空');history.go(-1);</script>";

	  exit;

	}
	if($name=="")

	{

	  echo "<script>alert('请输入姓名');history.go(-1);</script>";

	  exit;

	}
	if($weixin=="")

	{

	  echo "<script>alert('请输入微信号');history.go(-1);</script>";

	  exit;

	}
	if($phone=="")

	{

	  echo "<script>alert('请输入手机号码');history.go(-1);</script>";

	  exit;

	}

	$sql = "select id from tgs_agent where agentid='".$agentid."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('代理商编号已存在');history.go(-1);</script>";

	  exit;

	}
	
	$sql = "select id from tgs_agent where weixin='".$weixin."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('微信号已存在');history.go(-1);</script>";

	  exit;

	}
	
	$sql = "select id from tgs_agent where phone='".$phone."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){

	  echo "<script>alert('手机号已存在');history.go(-1);</script>";

	  exit;

	}
	
if(empty($idcard)){
}
else{
if(strlen($idcard)== 18){    
    //验证通过    
         
}else{    
  echo "<script>alert('身份证号格式不正确');history.go(-1);</script>";
  
   exit;
         
   }
}
	

if(empty($phone)){
}
else{
if(strlen($phone)== 11){    
    //验证通过    
         
}else{    
  echo "<script>alert('手机号格式不正确');history.go(-1);</script>";
  
   exit;
         
}}
	


	$sql="insert into tgs_agent (agentid,idcard,dengji,product,quyu,applytime,shuyu,qudao,about,addtime,jietime,name,tel,fax,phone,danwei,email,url,qq,weixin,wangwang,paipai,zip,dizhi,beizhu,sjdl,shzt,hmd,password)values('$agentid','$idcard','$dengji','$product','$quyu','$applytime','$shuyu','$qudao','$about','$addtime','$jietime','$name','$tel','$fax','$phone','$danwei','$email','$url','$qq','$weixin','$wangwang','$paipai','$zip','$dizhi','$beizhu','$sjdlid','$shzt','$hmd','$password')";

	//$sql="insert into tgs_agent set agentid = '	".$agentid."',product='".$product."',quyu='".$quyu."',shuyu='".$shuyu."',qudao='".$qudao."',about='".$about."',addtime='".$addtime."',jietime='".$jietime."',name='".$name."',tel='".$tel."',fax='".$fax."',phone='".$phone."',danwei='".$danwei."',email='".$email."',url='".$url."',qq='".$qq."',weixin='".$weixin."',wangwang='".$wangwang."',paipai='".$paipai."',zip='".$zip."',dizhi='".$dizhi."',beizhu='".$beizhu."'";



	mysql_query($sql);

	echo "<script>alert('添加成功');location.href='agent.php?'</script>";

	exit;

}



////为编辑获取数据

if($act == "edit"){   

    $editid = $_GET["id"];

	$sql="select * from tgs_agent where id='$editid' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	$agentid      = $arr["agentid"];
	$idcard      = $arr["idcard"];

$dengji      = $arr["dengji"];


	$product      = $arr["product"];

	$quyu         = $arr["quyu"];

	$shuyu        = $arr["shuyu"];
	$hmd        = $arr["hmd"];
	
	$sjdl        = $arr["sjdl"];
	
	$password        = $arr["password"];

	$qudao        = $arr["qudao"];

	$about        = $arr["about"];

	$addtime      = $arr["addtime"];

	$jietime      = $arr["jietime"];

	$name         = $arr["name"];

	$tel          = $arr["tel"];

	$fax          = $arr["fax"];

	$phone        = $arr["phone"];

	$danwei       = $arr["danwei"];

	$email        = $arr["email"];

	$url          = $arr["url"];

	$qq           = $arr["qq"];

	$weixin       = $arr["weixin"];

	$wangwang     = $arr["wangwang"];

	$paipai       = $arr["paipai"];

	$zip          = $arr["zip"];

	$dizhi          = $arr["dizhi"];

	$beizhu       = $arr["beizhu"];

	$rn         = "修改代理商信息";

｝

?>





<?php

}


/////修改代理信息//////////

if($act == "save_agentedit"){



    $editid     = $_REQUEST["editid"]; 

	$agentid      = trim($_REQUEST["agentid"]);
	$idcard      = trim($_REQUEST["idcard"]);
	$dengji      = trim($_REQUEST["dengji"]);

	$product      = trim($_REQUEST["product"]);

	$quyu         = trim($_REQUEST["quyu"]);

	$shuyu        = trim($_REQUEST["shuyu"]);

	$url          = strreplace(trim($_REQUEST["url"]));

	$qudao        = trim($_REQUEST["qudao"]);
	$hmd        = trim($_REQUEST["hmd"]);
	$sjdl        = trim($_REQUEST["sjdl"]);
	
	$password        = trim($_REQUEST["password"]);

	$about        = trim($_REQUEST["about"]);

	$addtime      = trim($_REQUEST["addtime"]);

	$jietime      = trim($_REQUEST["jietime"]);

	$name         = trim($_REQUEST["name"]);

	$tel          = trim($_REQUEST["tel"]);

	$fax          = trim($_REQUEST["fax"]);

	$phone        = trim($_REQUEST["phone"]);

	$danwei       = trim($_REQUEST["danwei"]);

	$email        = strreplace(trim($_REQUEST["email"]));	

	$qq           = trim($_REQUEST["qq"]);

	$weixin       = trim($_REQUEST["weixin"]);

	$wangwang     = trim($_REQUEST["wangwang"]);

	$paipai       = trim($_REQUEST["paipai"]);

	$zip          = trim($_REQUEST["zip"]);

	$dizhi        = strreplace(trim($_REQUEST["dizhi"]));

	$beizhu       = strreplace(trim($_REQUEST["beizhu"]));
	$sql="select * from tgs_agent where weixin='$sjdl' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	$sjdlid      = $arr["id"];
	if(empty($sjdl)){
}
else{

	if($sjdlid=="")

	{

	  echo "<script>alert('上级代理微信号不存在，请检查');history.go(-1);</script>";

	  exit;

	}
}

	if($editid == "")

	{

	  echo "<script>alert('ID参数有误');location.href='agent.php?'</script>";

	  exit;

	}

	if($editid=="")

	{

	  echo "<script>alert('代理商编号不能为空');location.href='?act=edit&id=".$editid."'</script>";

	  exit;

	}

	//$sql="update tgs_code set bianhao='$bianhao',riqi='$riqi',product='$product',zd1='$zd1',zd2='$zd2' where id='$editid' limit 1";

	

	$sql="update tgs_agent set agentid = '".$agentid."',password = '".$password."',idcard = '".$idcard."',dengji = '".$dengji."',hmd = '".$hmd."',sjdl = '".$sjdlid."',product='".$product."',quyu='".$quyu."',shuyu='".$shuyu."',qudao='".$qudao."',about='".$about."',addtime='".$addtime."',jietime='".$jietime."',name='".$name."',tel='".$tel."',fax='".$fax."',phone='".$phone."',danwei='".$danwei."',email='".$email."',url='".$url."',qq='".$qq."',weixin='".$weixin."',wangwang='".$wangwang."',paipai='".$paipai."',zip='".$zip."',dizhi='".$dizhi."',beizhu='".$beizhu."'where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);



	echo "<script>alert('修改成功');location.href='agent.php?'</script>";

	exit; 



}



/////审核//////////


if($act == "save_agentsh"){


$shzta = $_GET["shzta"];
    $editid     = $_REQUEST["editid"]; 
	

	$agentid      = trim($_REQUEST["agentid"]);


	

	if($editid == "")

	{

	  echo "<script>alert('ID参数有误');location.href='agent.php?'</script>";

	  exit;

	}

	if($editid=="")

	{

	  echo "<script>alert('代理商编号不能为空');location.href='?act=edit&id=".$editid."'</script>";

	  exit;

	}

	//$sql="update tgs_code set bianhao='$bianhao',riqi='$riqi',product='$product',zd1='$zd1',zd2='$zd2' where id='$editid' limit 1";

	

	$sql="update tgs_agent set shzt='$shzta' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);

if ($shzta=="2")
{echo "<script>alert('取消审核成功');location.href='agent.php?'</script>";}
else {
	
	echo "<script>alert('审核成功');location.href='agent.php?'</script>";
	}

	

	exit; 



}



 /////多选或全选删除功能//////////////

if($act == "delagent"){

	$chk = $_REQUEST["chk"];

	if(count($chk)>0){

	  $countchk = count($chk);

		for($i=0;$i<=$countchk;$i++)  

		{  

		 //echo  $chk[$i]."<br>"; 

		  mysql_query("delete from tgs_agent where id='$chk[$i]' limit 1"); 		  

		} 

		echo "<script>alert('删除成功');location.href='agent.php?'</script>";

	}

}



/////查询记录////////

if($act == "query_record")

{

  $code_list = array();

  $key       = trim($_REQUEST["key"]);

  $agpz        = trim($_REQUEST['agpz']);

  $sql="select * from tgs_hisagent where 1";

  if($key != ""){

    $sql.=" and keyword like '%$key%'";

  }  

  $sql.=" order by id desc";

  ///echo $sql;

  $result=mysql_query($sql); 

  if($agpz == ""){ 

    $pagesize = $cf['list_num'];//每页所要显示的数据个数。

	$agpz       = $cf['list_num'];

  }else{

	$pagesize = $agpz;

  }

  $total    = mysql_num_rows($result); 	

  $filename = "?act=query_record&keyword=".$key."&agpz=".$agpz."";

  $currpage  = intval($_REQUEST["page"]);

  if(!is_int($currpage))

	$currpage=1;

	if(intval($currpage)<1)$currpage=1;

    if(intval($currpage-1)*$pagesize>$total)$currpage=1;



	if(($total%$pagesize)==0){

	  $totalpage=intval($total/$pagesize); 

	}

	else

	  $totalpage=intval($total/$pagesize)+1;

	  if ($total!=0&&$currpage>1)

       mysql_data_seek($result,(($currpage-1)*$pagesize));

     $i=0;

     while($arr=mysql_fetch_array($result)) 

     { 

     $i++;

     if($i>$pagesize)break; 

         

		 $code_list[] = $arr;

	 }

?>



<?php

}



/////删除查询记录

if($act == "delete_history")

{

	$chk = $_REQUEST["chk"];

	if(count($chk)>0){

	  $countchk = count($chk);

		for($i=0;$i<=$countchk;$i++)  

		{  

		 //echo  $chk[$i]."<br>"; 

		  mysql_query("delete from tgs_hisagent where id='$chk[$i]' limit 1");		  

		} 

		echo "<script>alert('删除成功');location.href='?act=query_record'</script>";

	}

}





/////审核代理申请//////////


if($act == "save_sqdengji"){


    $editid     = $_REQUEST["editid"]; 
	
     $sqdj     = $_REQUEST["sqdj"]; 
	 
	$agentid      = trim($_REQUEST["agentid"]);


	

	if($editid == "")

	{

	  echo "<script>alert('ID参数有误');location.href='?'</script>";

	  exit;

	}

	if($editid=="")

	{

	  echo "<script>alert('代理商编号不能为空');location.href='?act=edit&id=".$editid."'</script>";

	  exit;

	}

	//$sql="update tgs_code set bianhao='$bianhao',riqi='$riqi',product='$product',zd1='$zd1',zd2='$zd2' where id='$editid' limit 1";

	

	$sql="update tgs_agent set dengji='$sqdj',sqdengji='' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);


	
	echo "<script>alert('确认升级成功');location.href='agent.php?'</script>";

	

	exit; 



}



/////删除请求记录//////////


if($act == "del_sqdengji"){


    $editid     = $_REQUEST["editid"]; 
	
     $sqdj     = $_REQUEST["sqdj"]; 
	 
	$agentid      = trim($_REQUEST["agentid"]);


	

	if($editid == "")

	{

	  echo "<script>alert('ID参数有误');location.href='agent.php?'</script>";

	  exit;

	}

	if($editid=="")

	{

	  echo "<script>alert('代理商编号不能为空');location.href='agent.php?act=edit&id=".$editid."'</script>";

	  exit;

	}

	//$sql="update tgs_code set bianhao='$bianhao',riqi='$riqi',product='$product',zd1='$zd1',zd2='$zd2' where id='$editid' limit 1";

	

	$sql="update tgs_agent set sqdengji='' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);


	
	echo "<script>alert('删除请求记录成功');location.href='agent.php?'</script>";

	

	exit; 



}


?>

<?php

//csv读取函数

function __fgetcsv(&$handle, $length = null, $d = ",", $e = '"')

{

      $d = preg_quote($d);

      $e = preg_quote($e);

      $_line = "";

      $eof   = false;

      while ($eof != true)

      {

         $_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));

         $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);

         if ($itemcnt % 2 == 0)

            $eof = true;

      }

      $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));      $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';

      preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);

      $_csv_data = $_csv_matches[1];

      for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++)

      {       $_csv_data[$_csv_i] = preg_replace("/^" . $e . "(.*)" . $e . "$/s", "$1", $_csv_data[$_csv_i]);

         $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);

      }

      return empty($_line) ? false : $_csv_data;

}

?>

</body>

</html>