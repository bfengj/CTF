
<?php

include 'head.php';

$act = $_GET["act"];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='style/css/global.css' rel='stylesheet' type='text/css' />
<link href='style/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='style/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='style/js/global.js'></script>
<SCRIPT type=text/javascript>
function SelectSkinID(Skin_ID,Trade_ID){
	if(confirm("您确定要选择此风格吗？")){
		location.href="skin.php?act=setagent&Skin_ID="+Skin_ID+"&Trade_ID="+Trade_ID;
	}
}

function SelectSkinID2(Skin_ID,Trade_ID){
	if(confirm("您确定要选择此风格吗？")){
		location.href="skin.php?act=setfw&Skin_ID="+Skin_ID+"&Trade_ID="+Trade_ID;
	}
}
</SCRIPT>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='style/js/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='style/css/shop.css' rel='stylesheet' type='text/css' />
    
   
    <script language="javascript">$(document).ready(shop_obj.skin_init);</script>
  
    <div id="skin" class="r_con_wrap">
      <ul>
        
       <script src="js/class31_newnews.js"></script>
          
            </ul>
      <div class="clear"></div>
    </div>
  </div>
</div>

<?php
if($act == "setagent"){

    $agentstyle = $_GET["Skin_ID"];
	
   
		  
		 $sql="update tgs_config set code_value='$agentstyle' where code='agent_themes' limit 1"; 
	

	//echo $sql;

	 mysql_query($sql) or die("err:".$sql);



	echo "<script>alert('设置代理模板成功');location.href='skin.php'</script>";

	exit; 

		}
		
		
if($act == "setfw"){

    $agentstyle = $_GET["Skin_ID"];
	
   
		  
		 $sql="update tgs_config set code_value='$agentstyle' where code='site_themes' limit 1"; 
	

	//echo $sql;

	 mysql_query($sql) or die("err:".$sql);



	echo "<script>alert('设置防伪模板成功');location.href='skin.php'</script>";

	exit; 

		}



    
?>
</body>
</html>