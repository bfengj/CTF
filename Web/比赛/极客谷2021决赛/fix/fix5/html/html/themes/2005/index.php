<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<title><?=$cf['site_name']?></title>

<meta name="keywords" content="<?=$cf['page_keywords']?>" />

<meta name="description" content="<?=$cf['page_desc']?>" />
<link rel="stylesheet" type="text/css" href="themes/<?=$cf['site_themes']?>/style/css/style.css" />
<script src="themes/<?=$cf['site_themes']?>/style/js/jquery.js"></script>
<script src="themes/<?=$cf['site_themes']?>/style/js/vector.js"></script>
<SCRIPT type="text/javascript" src="data/js/ajax.js"></SCRIPT>
<style type="text/css">
	.neibogg {
	padding-top: 20px;
	padding-right: 10px;
	padding-bottom: 10px;
	padding-left: 10px;
}
    .boxxaa {
	width: 320px;
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
    
    
</head>
<body>
<div id="container">
<div class="table">
<div class="logobox"><h1><img src="<?=$cf['fw_logo']?>"/></h1></div>
<table class="boxxaa" border="0" cellspacing="1" cellpadding="3" align="center">

    <tr>
      <td align="center"> 
     <input name='bianhao' id="bianhao" type="text" class="input" maxlength="18" value="请输入防伪码" onfocus="if(this.value=='请输入防伪码'){this.value='';}"  onblur="if(this.value==''){this.value='请输入防伪码';}" />
     <?php if($cf['yzm_status']==1){?><input name='yzm' id='yzm' type="text" class="yz_box f1" value="验证码" onfocus="if(this.value=='验证码'){this.value='';}"  onblur="if(this.value==''){this.value='验证码';}" maxlength="4" /><img src="data/code.php" alt="验证码" class="yz_code" onclick="window.location.reload()"/><?php } ?>
     
     <input name="" type="submit" class="submit" id="ButOK" onClick="return GetQuery();" value="查询" /> 
     
     <INPUT value='' type='hidden' name='search' id='search'> <INPUT value='<?=$cf['yzm_status']?>' type='hidden' name='yzm_status' id='yzm_status'>
        </td>
  
    </tr>

</table>
<div class="neibogg"><div class="contact">  

 

    <div id="tgs_result_str"><?php echo $result_str;?></div>

 

</div></div>
<div class="foot"><?=$cf['copyrighta']?></div>
</div>
<div id="output"></div>
</div>
<ul class="color">
<li></li>
<li></li>
<li></li>
<li></li>
</ul>
</body>
</html>