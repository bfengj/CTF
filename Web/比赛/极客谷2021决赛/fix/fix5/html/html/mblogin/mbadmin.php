<?php
error_reporting(0);
require("../data/session.php");
require("../data/head.php");
require('../data/reader.php');

//树形获取
function get_str($id = 0) { 
    global $str; 
    $sql = "select * from tgs_agent where sjdl= $id";  
    $result = mysql_query($sql);//查询pid的子类的分类 
    if($result && mysql_affected_rows()){//如果有子类 
      
        while ($row = mysql_fetch_array($result)) { //循环记录集
		   
            $str .= "" . $row['sjdl'] . ","; //构建字符串 
            get_str($row['id']); //调用get_str()，将记录集中的id参数传入函数中，继续查询下级 
        } 
        
    } 
    return $str; 
	
}






	
	
$huiyuan=$_SESSION["Adminname"];
$sql="select * from tgs_agent where weixin='$huiyuan' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	$acid      = $arr["id"];
	$aname      = $arr["name"];
	$aweixin      = $arr["weixin"];
	$adengji      = $arr["dengji"];
	
		
	$aac      = $acid;
	$chaxuna=get_str($aac);
	$chaxunb=1000000;
	$chaxunc ="$chaxuna$chaxunb";
	
	
?>


<?php 
$sql="select * from tgs_dengji where djname='$adengji' limit 1";


	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	
	
	$checkper      = $arr["checkper"];
	$editper      = $arr["editper"];
	$delper      = $arr["delper"];
	$sjcheckper     = $arr["sjcheckper"];
	
?>
<html><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=3.0, user-scalable=yes">

<meta content="yes" name="apple-mobile-web-app-capable">

<meta content="black" name="apple-mobile-web-app-status-bar-style">

<meta content="telephone=no" name="format-detection">

<title>

	经销商后台

</title>

<link rel="stylesheet" href="style_mb/css/Jingle.css?r=20150505">

  <link rel="stylesheet" href="style_mb/css/app.css"> 

    <link href="style_mb/css/alertify.core.css" rel="stylesheet" type="text/css">

    <link href="style_mb/css/alertify.default.css" rel="stylesheet" type="text/css">



</head>



<body>

  

    

<div id="aside_container">

</div>



<div id="section_container">

    <section id="index_section" class="active">

       <header>

            <nav class="left">

                  <a href="index.php" data-icon="previous" data-target="back"><i class="icon previous"></i>返回</a>

            </nav>

            <h1 class="title">

               经销商后台

            </h1>

            <nav class="right">

                <a data-target="section" data-icon="info" href="index.php?act=logout">

                    退出 

                </a>

            </nav>



        </header>  

  

   <div class="member_info">

         

            <div class="info">

                <b>

                    你好，<?=$aname?>&nbsp;  微信:<?=$aweixin?>


              </b>   

                <p>


                等级:<?=$adengji?> &nbsp; <span class="label label-success radius"><a href="agentlist.php?act=sj&id=<?=$acid?>"><font color="#ffffff">提升等级</font></a></span>

              </p> 
              
              

                

            </div>

        </div>

   <ul class="list inset app-list">

        <li>

               <i class="icon next" data-selected="selected"></i>

                <span class="icon bubbles"></span>

                  <a href="shouquan.php">

                     <strong>微信代理授权</strong>

                      <p>查询已有代理授权信息</p>

                </a>

            </li>

       



         <li>

               <i class="icon next"></i>

                <span class="icon tree"></span>

                  <a href="share.php">

                     <strong>下级代理邀请/添加</strong>

                      <p>查看或生成下级申请代理的邀请连接</p>

                </a>

            </li>

         <li>

               <i class="icon next"></i>

                <span class="icon user"></span>
 <?php
$sql="select * from tgs_agent where sjdl in ($chaxunc)";
$query=mysql_query($sql);
$total=mysql_num_rows($query);
?>
                  <a href="listdengji.php">

                     <strong>管理下级代理(<font color="#FF0000"><?php echo $total;?></font>)</strong>

                      <p>查看和管理所有下级代理信息</p>

                </a>

            </li>

          

            <li>

                <i class="icon next"></i>

                <span class="icon qrcode"></span>
                  <?php
$sql="select * from tgs_agent where sjdl in ($chaxunc) and shzt=2";
$query=mysql_query($sql);
$shtotal=mysql_num_rows($query);
?>

                <a href="agentlist.php?ucheck=2">

                    <strong>待审核代理(<font color="#FF0000"><?php echo $shtotal;?></font>)</strong>

                    <p>下级提交信息未审核代理列表</p>

                </a>

            </li>

			

  <?php 
		
		if ($sjcheckper==1)
		{
			
			
		?>  <li> <i class="icon next"></i>

                <span class="icon qrcode"></span>
	
                <a href="dengjish.php?">
               <?php
$sql="select * from tgs_agent where sjdl in ($chaxunc) and  sqdengji <>''";
$query=mysql_query($sql);
$sqtotal=mysql_num_rows($query);
?>

                    <strong>代理等级升级审核(<font color="#FF0000"><?php echo $sqtotal;?></font>)</strong> 

                    <p>查看和管理代理提升等级申请</p>

                </a>

            </li> 

        <?php }
	    else {
			?> 
        <?php
			}
		
		 ?>
                

               

            <li>

                

                <i class="icon next"></i>

                <span class="icon search "></span>

                <a href="agentsc.php?">

                    <strong>查找代理信息</strong> 

                     <p>根据微信号查找代理信息</p>

                </a>

            </li>  

           

        

           <li>

                

                <i class="icon next"></i>

                <span class="icon pencil-2"></span>

                <a href="agentlist.php?act=per&id=<?=$acid?>">

                    <strong>修改个人资料及密码</strong> 

                    <p>修改个人信息及登录密码</p>

                </a>

            </li> 

 

       

          

        </ul>



    </section>



    

</div>

    





</body></html>