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
	$aqudao      = $arr["dengji"];

?>
<html><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=3.0, user-scalable=yes">

<meta content="yes" name="apple-mobile-web-app-capable">

<meta content="black" name="apple-mobile-web-app-status-bar-style">

<meta content="telephone=no" name="format-detection">
 <style>
        #qrcode{
            /*text-align: center;*/
            /*display: table-cell;*/
            /*width: 96px;*/
            /*height: 96px;*/
            /*vertical-align:middle;*/
            /*position: relative;*/
        }
    </style>
<title>

	经销商后台

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

                <h1 class="title">代理邀请/添加

              </h1>

                <nav class="right">

                    <a data-target="section" data-icon="info" href="#" id="manualBtn"> </a>

                </nav> 

            </header> 

         <div class="scroll-area-list" id="codelListArea">

              <ul class="list">

                  

                             <li>

                                  <i class="icon next"></i>

                                   <strong><?=$aname?></strong>

                                   <p>授权号:<?=$aagentid?></p>
                                    <p>微信号:<?=$aweixin?></p>

                                   <p>代理级别:<?=$aqudao?></p>

                                  

                                 

                               <div class="tag" style="position: absolute;right:20px;top: 50%;margin-top: -9px;">

 <a href="../apply.php?wx=<?=$aweixin?>"><span style="color:#ffffff;padding:5px;line-height: 20px;">查看专用申请链接</span> </a>

                                   </div>

                                     

                                  

                              </li>

							   <li>

                                  <i class="icon next"></i>

                                   <strong>复制下方专用链接</strong>

								   <p><?=$cf['site_url']?>apply.php?wx=<?=$aweixin?></p>
                                   
                                  
                                   
           <div class="qrcode"><img src="url_qrcode.php?url=<?=$cf['site_url']?>apply.php?wx=<?=$aweixin?>" alt="二维码" width="200px"></div>


                              </li>

                          

               

              </ul>

             

         

             

          </div> 

            

            



        </section>





    </div> 

    











</body></html>