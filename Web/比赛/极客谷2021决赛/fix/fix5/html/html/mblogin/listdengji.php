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
<html><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=3.0, user-scalable=yes">

<meta content="yes" name="apple-mobile-web-app-capable">

<meta content="black" name="apple-mobile-web-app-status-bar-style">

<meta content="telephone=no" name="format-detection">

<title>

	代理品牌列表

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

                <h1 class="title">按等级查看

              </h1>

                <nav class="right">

                    <a data-target="section" data-icon="info" href="#" id="manualBtn"> </a>

                </nav> 

            </header> 

           <div class="scroll-area-list" id="codelListArea">
  <div class="weinxPlane">
    <ul class="list app-list" >
      <li class="divider">按等级查看</li>
      
      
      <?php

		 $sqldj = "select djname from tgs_dengji where djname<>'' order by jibie DESC";

		 $resdj = mysql_query($sqldj);
		 
         
		 
		 while($arr = mysql_fetch_array($resdj)){	
		$djname1 .= $arr['djname'].' ';
		 }
		$djname =$djname1;
		
		?>
        
        
        

		<?php

		 $sql = "select * from tgs_dengji where djname<>'' order by jibie DESC";

		 $res = mysql_query($sql);
		 
        
		 while($arr = mysql_fetch_array($res)){		
       
		?>

       <li>
        <i class="icon xiajiico"></i> <a href="agentlist.php?udengji=<?=$arr["djname"];?>"><strong><?php echo $arr["djname"];?> </strong> </a>
         <label style="margin-right:10px; color:#FF0000">
         
           <?php
$sql="select * from tgs_agent where sjdl in ($chaxunc) and dengji='".$arr["djname"]."'";

$query=mysql_query($sql);
$total=mysql_num_rows($query);
echo $total;
?>
         </label>
        </li>
        
        

		<?php

		}
		
		

	
		?>
         <li>
        <i class="icon xiajiico"></i> <a href="agentlist.php?usjdl=<?=$acid;?>"><strong>我的直接代理 </strong> </a>
         <label style="margin-right:10px; color:#FF0000">
         
           <?php
$sql="select * from tgs_agent where sjdl in ($chaxunc) and sjdl='".$acid."'";

$query=mysql_query($sql);
$total=mysql_num_rows($query);
echo $total;
?>
         </label>
        </li>
        
        </ul>
  </div>
</div>

 

            

            



        </section>





    </div> 

    











</body></html>