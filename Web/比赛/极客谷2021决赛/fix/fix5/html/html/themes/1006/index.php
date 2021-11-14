<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<title><?=$cf['site_name']?></title>

<meta name="keywords" content="<?=$cf['page_keywords']?>" />

<meta name="description" content="<?=$cf['page_desc']?>" />
	<link rel="stylesheet" type="text/css" href="themes/<?=$cf['agent_themes']?>/style/css/style.css" />
    
    <style type="text/css">
	.neibogg {
	padding-top: 20px;
	padding-right: 10px;
	padding-bottom: 10px;
	padding-left: 10px;
}
.neibogg a {
	color:#ffffff;
}
    .boxxaa {
	width: 420px;
}
@media screen and (max-width: 600px) { 
.boxxaa {
	width: 320px;
}
.table{ width: 320px; position: absolute;margin:10% auto 0; left:0; right:0; z-index:999}

.input {
	padding: 10px;
	border: 1px solid #d5d9da;
	border-radius: 5px;
	box-shadow: 0 0 5px #e8e9eb inset;
	width: 300px; /* 400 (parent) - 40 (li margins) -  10 (span paddings) - 20 (input paddings) - 2 (input borders) */
	font-size: 1em;
	outline: 0; /* remove webkit focus styles */
	margin-bottom: 5px;
}
.logobox img{ width:320px;}
}
    </style>
	<!--[if IE]>
		<script src="style/js/html5shiv.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="table">
<div class="logobox"><h1><img src="<?=$cf['agent_logo']?>"/></h1></div>
<table class="boxxaa" border="0" cellspacing="1" cellpadding="3">

    <tr>
      <td align="center"> 
      <script>
function CheckSearchForm(){
        if(document.getElementById("searchform").keyword.value=="")
        {
                alert("请输入微信号或手机号");
                return false;
        }
     
}
</script>
      <form name="searchform" id="searchform" method="get" action="search.php?act=query" onSubmit="return CheckSearchForm();">
          <input type="hidden" name="act" id="act" value="query">
            <input name="keyword" id="keyword" class="input" type="text" placeholder="请输入微信号或手机号">
         <?php if($cf['yzm_status']==1){?>验证码：

   <input name='yzm' id='yzm' class="inputYzm" type="text" value="" maxlength="4">

   <img src="data/code.php" alt="验证码" title="点击刷新" class="code" onClick="window.location.reload()"/>&nbsp; <?php } ?>
   
     <input name="submit" type="submit" id="submit" value="查询" class="submit" onClick="return GetQuery();">
     <input name="button" type="button" id="submit" value="申请" class="submit" onClick="window.open('AddInfo.php')">
       
     
   <INPUT value='' type='hidden' name='search' id='search'>

   <INPUT value='<?=$cf['yzm_status']?>' type='hidden' name='yzm_status' id='yzm_status'>
   </form>
   
   

        </td>
      
    </tr>

</table>
<div class="neibogg"><div class="contact">  

 

    <div id="tgs_result_str"><?php echo $result_str;?></div>

 

</div></div>
<div class="foot"><?=$cf['copyrighta']?></div>
</div>
<div class="stars"></div>
<script src='themes/<?=$cf['agent_themes']?>/style/js/jquery.min.js'></script>
<script src='themes/<?=$cf['agent_themes']?>/style/js/stopExecutionOnTimeout.js?t=1'></script>
</body>
</html>