<?php
error_reporting(0);
require("../data/session_admin.php");
require("../data/head.php");
require('../data/reader.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?=$cf['site_name']?></title>
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->


<link href="../data/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>








<div style="width: 100%;padding:2%;">

<script type="text/javascript" src="agenttree/js/jquery.min.js"></script>
<script type="text/javascript" src="agenttree/js/jquery.jOrgChart.js"></script>
<link rel="stylesheet" href="agenttree/css/jquery.jOrgChart.css"/>

<style type="text/css"> 
	/* Custom chart styling */
.jOrgChart {
  margin                : 10px;
  padding               : 20px;
}

/* Custom node styling */
.jOrgChart .node {

    background: url('agenttree/images/img/avatar.png');
    font-size: 12px;
    border-radius: 8px;
    color: #191919;
    height: 35px;
    width: 80px;
    padding-top: 30px;
    font-weight: bold;
    background-size: 25px 25px;
    background-repeat: no-repeat;
    background-position: center top;
        background-position-y: 5px;
}

.jOrgChart > table > tbody > tr:first-child .node {
	background: url('agenttree/images/img/admin.png');
        background-size: 25px 25px;
    background-repeat: no-repeat;
    background-position: center top;
}
	.node p{
		font-family 	: tahoma;
		font-size 		: 10px;
		line-height 	: 11px;
		padding 		: 2px;
	}

	.jOrgChart td span {
		display: block;
    font-size: 12px;
    color: #E6A4EA;
	}
</style>

<div class="total">




<ul id="org" style="display:none">
<li>
总公司
<?php
//树形获取
function get_str($id = 0) { 
    global $str; 
    $sql = "select * from tgs_agent where sjdl= $id";
    $result = mysql_query($sql);//查询pid的子类的分类 
    if($result && mysql_affected_rows()){//如果有子类 
        $str .= '<ul>'; 
        while ($row = mysql_fetch_array($result)) { //循环记录集 
		 $str .= '<li>'; 
            $str .= "" . $row['id'] . "-" . $row['name'] . "</br>". $row['dengji'] .""; //构建字符串 
            get_str($row['id']); //调用get_str()，将记录集中的id参数传入函数中，继续查询下级
			
			$str .= '</li>';  
        } 
        $str .= '</ul>'; 
    } 
    return $str; 
} 

echo get_str(0);
 ?>
 
 


</li>
</ul>




<div id="org_chart">
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
    	$("#org").jOrgChart({
    		'chartElement':'#org_chart'
    	});
	});
</script>
</div>
</div>






                    
                        
</body>
</html>