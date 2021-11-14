<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$cf['site_name']?></title>

<meta name="keywords" content="<?=$cf['page_keywords']?>" />

<meta name="description" content="<?=$cf['page_desc']?>" />

 
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

	<link rel="stylesheet" type="text/css" href="themes/<?=$cf['site_themes']?>/style/css/style.css" />
    
    <SCRIPT type="text/javascript" src="data/js/ajax.js"></SCRIPT>
	<!--[if IE]>
		<script src="style/js/html5shiv.min.js"></script>
	<![endif]-->
    
    
<style>
.select{
	padding:9px 9px;
	border:1px solid #d5d9da;
	border-radius:5px;
	box-shadow: 0 0 5px #e8e9eb inset;
	width:100px; /* 400 (parent) - 40 (li margins) -  10 (span paddings) - 20 (input paddings) - 2 (input borders) */
	font-size:1em;
	outline:0; /* remove webkit focus styles */
}

.neibogg {
	padding-top: 20px;
	padding-right: 15px;
	padding-bottom: 10px;
	padding-left: 15px;
	color: #FFF;
}
.neibogg a {
	
	color: #FFF;
}
   
.table{ width:400px; margin:10px auto;}
  .tableaa {
	width: 400px;
}
@media screen and (max-width: 800px) { 
.table {
	width: 300px;
}
.tableaa {
	width: 300px;
}
.logobox img{ width:250px;}
.input {
	padding: 10px;
	border: 1px solid #d5d9da;
	border-radius: 5px;
	box-shadow: 0 0 5px #e8e9eb inset;
	width: 150px; /* 400 (parent) - 40 (li margins) -  10 (span paddings) - 20 (input paddings) - 2 (input borders) */
	font-size: 1em;
	outline: 0; /* remove webkit focus styles */
	margin-bottom: 5px;
}

.neibogg {
	padding-top: 20px;
	
	padding-bottom: 10px;
	
	color: #FFF;
}
}


</style>


</head>
<body>
<div class="table">
<div class="logobox"><h1><img src="<?=$cf['fw_logo']?>"/></h1></div>
<table align="center" class="tableaa" border="0" cellspacing="1" cellpadding="3">

    <tr>
      <td align="center"> 
     <input name='bianhao' id="bianhao" type="text" class="input" maxlength="18" value="请输入防伪码" onfocus="if(this.value=='请输入防伪码'){this.value='';}"  onblur="if(this.value==''){this.value='请输入防伪码';}" />
    <?php if($cf['yzm_status']==1){?><input name='yzm' id='yzm' type="text" class="yz_box f1" value="验证码" onfocus="if(this.value=='验证码'){this.value='';}"  onblur="if(this.value==''){this.value='验证码';}" maxlength="4" /><img src="data/code.php" alt="验证码" class="yz_code" onclick="window.location.reload()"/><?php } ?><input name="" type="submit" class="submit" id="ButOK" onClick="return GetQuery();" value="点击查询" /> <INPUT value='' type='hidden' name='search' id='search'> <INPUT value='<?=$cf['yzm_status']?>' type='hidden' name='yzm_status' id='yzm_status'>
     
     
     
   
<div class="neibogg"><div class="contact">  

 

    <div id="tgs_result_str"><?php echo $result_str;?></div>

 

</div></div>
        </td>
      
    </tr>

</table>

<div class="foot"><?=$cf['copyrighta']?></div>
</div>
<div class="stars"></div>
<script src='themes/<?=$cf['site_themes']?>/style/js/jquery.min.js'></script>
<script src='themes/<?=$cf['site_themes']?>/style/js/stopExecutionOnTimeout.js?t=1'></script>
</body>
</html>