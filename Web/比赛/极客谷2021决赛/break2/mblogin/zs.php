<?php
error_reporting(0);
require("../data/session.php");
require("../data/head.php");
require('../data/reader.php');

$huiyuan=$_SESSION["Adminname"];
$sql="select * from tgs_agent where weixin='$huiyuan' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);

	$acid      = $arr["id"];
	$aname      = $arr["name"];
	$aweixin      = $arr["weixin"];
	$aagentid      = $arr["agentid"];
	$aqudao      = $arr["qudao"];

?>
<html><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=3.0, user-scalable=yes">

<meta content="yes" name="apple-mobile-web-app-capable">

<meta content="black" name="apple-mobile-web-app-status-bar-style">

<meta content="telephone=no" name="format-detection">

<title>

	授权证书

</title>

<link rel="stylesheet" href="style_mb/css/Jingle.css?r=20150505">

  <link rel="stylesheet" href="style_mb/css/app.css"> 

   

    <link href="style_mb/css/alertify.core.css" rel="stylesheet" type="text/css">

    <link href="style_mb/css/alertify.default.css" rel="stylesheet" type="text/css">

  

</head>

<body> 

    <div id="aside_container" style="display: block;">

    </div> 

    <div id="section_container">

        <section id="index_section" class="active">

            <header>

                <nav class="left">

                    <a href="javascript:history.back(-1)" data-icon="previous" data-target="back"><i class="icon previous"></i>返回</a>

                </nav>

                <h1 class="title">授权证书

              </h1>

                <nav class="right">

                    <a data-target="section" data-icon="info" href="#" id="manualBtn"> </a>

                </nav> 

            </header> 

         <div class="scroll-area-list" id="codelListArea">

              <p align="center">
<img src="../zs.php?&act=query&keyword=<?php echo $_GET[wx]; ?>&submit=%E6%9F%A5%E8%AF%A2&search=no&yzm_status=0" alt="myImage" width="100%" />
</p>

             

         

             

          </div> 

            

            



        </section>





    </div> 

    











</body></html>