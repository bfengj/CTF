<?php
error_reporting(0);
require("../data/session_admin.php");
require("../data/head.php");
$act = $_GET["act"];
///导出防伪码/////////////////////////////////////////////
if($act=="export_code")
{
	$chk = $_REQUEST["chk"];
	$chk = explode(",",$chk);///转化为数组
	$file_encoding = $_POST["file_encoding"];	
	$a   = 0;
	if(count($chk) > 0){		
		if($_POST['field_bianhao']=="1"){
		 $content  = "商品防伪码";
		 $a        = 1;
		}
		if($_POST['field_riqi']=="1"){
		 $content .= ",有效日期";
		 $a        = 1;
		}
		if($_POST['field_product']==1){
		 $content .= ",产品类型";
		 $a        = 1;
		}
		if($_POST['field_zd1']==1){
		 $content .= ",保留字段1";
		 $a        = 1;
		}
		if($_POST['field_zd2']==1){
		 $content .= ",保留字段2";
		 $a        = 1;
		}
		$content  .= "\n";
		if($a == 0){
		    header("content-Type: text/html; charset=utf-8");
	        echo "<script>alert('请选择要导出的防伪码字段');history.back();</script>";
	        exit;
		}
		$countchk = count($chk);
		for($i=0;$i<$countchk;$i++)  
		{ 
		  $sql="select * from tgs_code where id='$chk[$i]' limit 1";
		  $res=mysql_query($sql);
		  while($arr=mysql_fetch_array($res)){
			  if($_POST['field_bianhao']=="1"){
			  $content .= $arr["bianhao"];
			  }
			  if($_POST['field_riqi']=="1"){
			  $content .= ",".$arr["riqi"];
			  }
			  if($_POST['field_product']=="1"){
			  $content .= ",".$arr["product"];
			  }
			  if($_POST['field_zd1']=="1"){
			  $content .= ",".$arr["zd1"];
			  }
			  if($_POST['field_zd2']=="1"){
			  $content .= ",".$arr["zd2"];
			  }
			  $content .= "\n";
		  }///while结束
		}//for结束

		if($file_encoding == "gbk"){
		 $content = iconv("utf-8", "gb2312"."//IGNORE", $content);
		}
		//$content = ob_gzip($content);////压缩文件
		$filename = "../upload/Code_csv_".date("Ymd").".csv";///临时csv文件名称
		$fp = fopen($filename,'w+');//生成CSV文件
		if(fwrite($fp,$content)){		
		  header("content-Type: text/html; charset=utf-8");
		  echo "生成csv文件成功，<a href='".$filename."' target='_blank'>右击'目标另存为'文档</a>，下载后<a href='?act=delete_file&file=".$filename."'>删除此CSV文档</a>";
		}else{
		  header("content-Type: text/html; charset=utf-8");
		  echo "无法写入导出内容，./upload文件夹应该为可读写权限。";
		}
		fclose($fp);
     }else{
	   header("content-Type: text/html; charset=utf-8");
	   echo "<script>alert('请选择要导出的防伪码');window.location.href='admin.php'</script>";
	   exit;
     }
}

///导出防伪码/////////////////////////////////////////////
if($act=="exportall_code")
{
	$chk = $_REQUEST["chk"];
	$chk = explode(",",$chk);///转化为数组
	$file_encoding = $_POST["file_encoding"];	
	$a   = 0;
	if(count($chk) > 0){		
		if($_POST['field_bianhao']=="1"){
		 $content  = "商品防伪码";
		 $a        = 1;
		}
		if($_POST['field_riqi']=="1"){
		 $content .= ",有效日期";
		 $a        = 1;
		}
		if($_POST['field_product']==1){
		 $content .= ",产品类型";
		 $a        = 1;
		}
		if($_POST['field_zd1']==1){
		 $content .= ",保留字段1";
		 $a        = 1;
		}
		if($_POST['field_zd2']==1){
		 $content .= ",保留字段2";
		 $a        = 1;
		}
		$content  .= "\n";
		if($a == 0){
		    header("content-Type: text/html; charset=utf-8");
	        echo "<script>alert('请选择要导出的防伪码字段');history.back();</script>";
	        exit;
		}
		$countchk = count($chk);
		for($i=0;$i<$countchk;$i++)  
		{ 
		  $sql="select * from tgs_code";
		  $res=mysql_query($sql);
		  while($arr=mysql_fetch_array($res)){
			  if($_POST['field_bianhao']=="1"){
			  $content .= $arr["bianhao"];
			  }
			  if($_POST['field_riqi']=="1"){
			  $content .= ",".$arr["riqi"];
			  }
			  if($_POST['field_product']=="1"){
			  $content .= ",".$arr["product"];
			  }
			  if($_POST['field_zd1']=="1"){
			  $content .= ",".$arr["zd1"];
			  }
			  //if($_POST['field_zd2']=="1"){
			  //$content .= ",".$arr["zd2"];
			  //}
			  $content .= "\n";
		  }///while结束
		}//for结束

		if($file_encoding == "gbk"){
		 $content = iconv("utf-8", "gb2312"."//IGNORE", $content);
		}
		//$content = ob_gzip($content);////压缩文件
		$filename = "../upload/Code_csv_".date("Ymd").".csv";///临时csv文件名称
		$fp = fopen($filename,'w+');//生成CSV文件
		if(fwrite($fp,$content)){		
		  header("content-Type: text/html; charset=utf-8");
		  echo "生成csv文件成功，<a href='".$filename."' target='_blank'>右击'目标另存为'文档</a>，下载后<a href='?act=delete_file&file=".$filename."'>删除此CSV文档</a>";
		}else{
		  header("content-Type: text/html; charset=utf-8");
		  echo "无法写入导出内容，./upload文件夹应该为可读写权限。";
		}
		fclose($fp);
     }else{
	   header("content-Type: text/html; charset=utf-8");
	   echo "<script>alert('请选择要导出的防伪码');window.location.href='admin.php'</script>";
	   exit;
     }
}


elseif($act == "delete_file")//未实现删除功能
{
  $filename = $_GET['filename'];
  unlink($filename);
  header("content-Type: text/html; charset=utf-8");
  echo "<script>alert('CSV文档删除成功');window.close()</script>";
  exit;
}

/////////////////////
///导出代理商信息/////////////////////////////////////////////
if($act=="export_agent")
{
	$chk = $_REQUEST["chk"];
	$chk = explode(",",$chk);///转化为数组
	$file_encoding = $_POST["file_encoding"];	
	$a   = 0;
	if(count($chk) > 0){		
		if($_POST['field_agentid']=="1"){
		 $content  = "代理商编号";
		 $a        = 1;
		}
		if($_POST['field_name']==1){
		 $content .= ",姓名";
		 $a        = 1;
		}
		if($_POST['field_idcard']==1){
		 $content .= ",身份证号";
		 $a        = 1;
		}
		if($_POST['field_weixin']==1){
		 $content .= ",微信";
		 $a        = 1;
		}
		if($_POST['field_phone']==1){
		 $content .= ",手机";
		 $a        = 1;
		}
		if($_POST['field_qq']==1){
		 $content .= ",QQ";
		 $a        = 1;
		}
		if($_POST['field_quyu']==1){
		 $content .= ",代理区域";
		 $a        = 1;
		}
		if($_POST['field_product']=="1"){
		 $content .= ",代理产品";
		 $a        = 1;
		}
		if($_POST['field_dengji']=="1"){
		 $content .= ",代理等级";
		 $a        = 1;
		}
		if($_POST['field_sjdl']=="1"){
		 $content .= ",上级代理ID";
		 $a        = 1;
		}
		if($_POST['field_addtime']==1){
		 $content .= ",代理开始时间";
		 $a        = 1;
		}
		if($_POST['field_jietime']==1){
		 $content .= ",代理结束时间";
		 $a        = 1;
		}
		
		if($_POST['field_shuyu']==1){
		 $content .= ",个人/公司";
		 $a        = 1;
		}
		if($_POST['field_qudao']==1){
		 $content .= ",代理渠道";
		 $a        = 1;
		}
		if($_POST['field_url']==1){
		 $content .= ",网址";
		 $a        = 1;
		}
		if($_POST['field_about']==1){
		 $content .= ",代理商介绍";
		 $a        = 1;
		}
		
		if($_POST['field_tel']==1){
		 $content .= ",电话";
		 $a        = 1;
		}
		if($_POST['field_fax']==1){
		 $content .= ",传真";
		 $a        = 1;
		}
		
		if($_POST['field_danwei']==1){
		 $content .= ",单位";
		 $a        = 1;
		}
		if($_POST['field_email']==1){
		 $content .= ",邮箱";
		 $a        = 1;
		}
		
		
		if($_POST['field_wangwang']==1){
		 $content .= ",旺旺";
		 $a        = 1;
		}
		if($_POST['field_paipai']==1){
		 $content .= ",拍拍";
		 $a        = 1;
		}
		if($_POST['field_zip']==1){
		 $content .= ",邮编";
		 $a        = 1;
		}
		if($_POST['field_dizhi']==1){
		 $content .= ",地址";
		 $a        = 1;
		}
		if($_POST['field_beizhu']==1){
		 $content .= ",备注";
		 $a        = 1;
		}
		
		$content  .= "\n";
		if($a == 0){
		    header("content-Type: text/html; charset=utf-8");
	        echo "<script>alert('请选择要导出的防伪码字段');history.back();</script>";
	        exit;
		}
		$countchk = count($chk);
		for($i=0;$i<$countchk;$i++)  
		{ 
		  $sql="select * from tgs_agent where id='$chk[$i]' limit 1";
		  $res=mysql_query($sql);
		  while($arr=mysql_fetch_array($res)){
		 if($_POST['field_agentid']=="1"){
		$content .= $arr["agentid"];
		}
		if($_POST['field_name']==1){
		$content .= ",".$arr["name"];
		}
		if($_POST['field_idcard']==1){
		$content .= ",".$arr["idcard"];
		}
		if($_POST['field_weixin']==1){
		$content .= ",".$arr["weixin"];
		}
		if($_POST['field_phone']==1){
		 $content .= ",".$arr["phone"];
		}
		if($_POST['field_qq']==1){
		$content .= ",".$arr["qq"];
		}
		if($_POST['field_quyu']==1){
		$content .= ",".$arr["quyu"];
		}
		if($_POST['field_product']=="1"){
		$content .= ",".$arr["product"];
		}
		if($_POST['field_dengji']=="1"){
		$content .= ",".$arr["dengji"];
		}
		if($_POST['field_sjdl']=="1"){
		 $content .= ",".$arr["sjdl"];
		}
		if($_POST['field_addtime']==1){
		$content .= ",".$arr["addtime"];
		}
		if($_POST['field_jietime']==1){
		$content .= ",".$arr["jietime"];
		}
		
		if($_POST['field_shuyu']==1){
		$content .= ",".$arr["shuyu"];
		}
		if($_POST['field_qudao']==1){
		$content .= ",".$arr["qudao"];
		}
		if($_POST['field_url']==1){
		$content .= ",".$arr["url"];
		}
		if($_POST['field_about']==1){
		$content .= ",".$arr["about"];
		}
		
		if($_POST['field_tel']==1){
		$content .= ",".$arr["tel"];
		}
		if($_POST['field_fax']==1){
		 $content .= ",".$arr["fax"];
		}
		
		if($_POST['field_danwei']==1){
		$content .= ",".$arr["danwei"];
		}
		if($_POST['field_email']==1){
		$content .= ",".$arr["email"];
		}
		
		
		if($_POST['field_wangwang']==1){
		$content .= ",".$arr["wangwang"];
		}
		if($_POST['field_paipai']==1){
		 $content .= ",".$arr["paipai"];
		}
		if($_POST['field_zip']==1){
		$content .= ",".$arr["zip"];
		}
		if($_POST['field_dizhi']==1){
		$content .= ",".$arr["dizhi"];
		}
		if($_POST['field_beizhu']==1){
		$content .= ",".$arr["beizhu"];
		}
			 
			  //开这个会转行，很郁闷哪！
			  //if($_POST['field_beizhu']=="1"){
			  //$content .= ",".$arr["beizhu"];
			  //}
			  $content .= "\n";
		  }///while结束
		}//for结束

		if($file_encoding == "gbk"){
		 $content = iconv("utf-8", "gb2312"."//IGNORE", $content);
		}
		//$content = ob_gzip($content);////压缩文件
		$filename = "../upload/Agent_csv_".date("Ymd").".csv";///临时csv文件名称
		$fp = fopen($filename,'w+');//生成CSV文件
		if(fwrite($fp,$content)){		
		  header("content-Type: text/html; charset=utf-8");
		  echo "生成csv文件成功，<a href='".$filename."' target='_blank'>右击'目标另存为'文档</a>，下载后<a href='?act=delete_file&file=".$filename."'>删除此CSV文档</a>";
		}else{
		  header("content-Type: text/html; charset=utf-8");
		  echo "无法写入导出内容，upload文件夹应该为可读写权限。";
		}
		fclose($fp);
     }else{
	   header("content-Type: text/html; charset=utf-8");
	   echo "<script>alert('请选择要导出的代理商信息');window.location.href='admin.php'</script>";
	   exit;
     }
}
elseif($act == "delete_file")//未实现删除功能
{
  $filename = $_GET['$filename'];
  unlink($filename);
  //system('del $filename');
  //unlink(iconv("UTF-8","gb2312","'.$filename.'"));
  header("content-Type: text/html; charset=utf-8");
  echo "<script>alert('CSV文档删除成功');window.close()</script>";
  exit;
}
?>