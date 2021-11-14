<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<title><?=$cf['site_name']?></title>

<meta name="keywords" content="<?=$cf['page_keywords']?>" />

<meta name="description" content="<?=$cf['page_desc']?>" />
<link href="themes/<?=$cf['site_themes']?>/style/css/style.css" rel="stylesheet" type="text/css" />
<script src="themes/<?=$cf['site_themes']?>/style/js/rainyday.js" type="text/javascript"></script>
<script type = "text/javascript" >
    function demo() {
        var a = new RainyDay('canvas', 'demo2', window.innerWidth, window.innerHeight);
        a.gravity = a.GRAVITY_NON_LINEAR;
        a.trail = a.TRAIL_DROPS;
        a.VARIABLE_GRAVITY_ANGLE = Math.PI / 8;
        a.rain([a.preset(0, 2, 0.5), a.preset(4, 4, 1)], 50)
    }
if (self != top) {
    window.top.location.replace(self.location)
}
var obj = window.location.href;
obj = obj.toLowerCase();
obj = obj.substr(7);
if (obj.indexOf("www.") == 0) {
    obj = obj.substr(4)
}


function CheckSearchForm() {
    if (document.getElementById("searchform").keyword.value == "") {
        alert("请选择查询条件或输入查询内容");
        return false
    }
    return true
} </script>


<style type="text/css">
	.neibogg {
	padding-top: 20px;
	padding-right: 15px;
	padding-bottom: 10px;
	padding-left: 15px;
	color: #FFF;
	text-align:left;
}
.neibogg a{
	color: #ffffff;
	
}

    .boxxaa {
	width: 500px;
}
@media screen and (max-width: 800px) { 
.boxxaa {
	width: 320px;
}


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

.logobox img{ width:320px;}
}
    </style>
    
     <SCRIPT type="text/javascript" src="data/js/ajax.js"></SCRIPT>
</head>
<body onLoad="demo();">
<div class="main_box">
<div class="logobox"><h1><img src="<?=$cf['fw_logo']?>"/></h1></div>
 
<table border="0" cellspacing="1" cellpadding="3" class="table boxxaa">


    <tr>
      <td>
      
      <input name='bianhao' id="bianhao" type="text" class="input" maxlength="18" value="请输入防伪码" onfocus="if(this.value=='请输入防伪码'){this.value='';}"  onblur="if(this.value==''){this.value='请输入防伪码';}" />
     <?php if($cf['yzm_status']==1){?><input name='yzm' id='yzm' type="text" class="yz_box f1" value="验证码" onfocus="if(this.value=='验证码'){this.value='';}"  onblur="if(this.value==''){this.value='验证码';}" maxlength="4" /><img src="data/code.php" alt="验证码" class="yz_code" onclick="window.location.reload()"/><?php } ?>
     
     <input name="" type="submit" class="submit" id="ButOK" onClick="return GetQuery();" value="查询" /> 
     
     <INPUT value='' type='hidden' name='search' id='search'> <INPUT value='<?=$cf['yzm_status']?>' type='hidden' name='yzm_status' id='yzm_status'>
 
        </td>
    
    </tr>
    <tr><td><div class="neibogg"><div class="contact">  

 

    <div id="tgs_result_str"><?php echo $result_str;?></div>

 

</div></div></td></tr>

</table>



<div class="foot"><?=$cf['copyrighta']?></div>
</div>
		<img id="demo2" src="themes/<?=$cf['site_themes']?>/img/1.jpg" />
	  <div id="cholder">
			<canvas id="canvas"></canvas>
		</div>
<span id="musicControl">
    <a id="mc_play" class="on" >
		<audio id="musicfx" loop="loop" autoplay="autoplay">
			<source src="themes/1003/img/music.mp3" type="audio/mpeg" />
		</audio>
	</a>
</span>
<script language="JavaScript" src="themes/<?=$cf['site_themes']?>/style/js/music.js" type="text/javascript" ></script>

</body>
</html>