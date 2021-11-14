
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$cf['site_name']?></title>

<meta name="keywords" content="<?=$cf['page_keywords']?>" />

<meta name="description" content="<?=$cf['page_desc']?>" />
 <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="themes/<?=$cf['agent_themes']?>/style/css/login.css" rel="stylesheet" type="text/css" />
<script language="javascript">

function Search()
{
if(FrmSearch.UNumber.value==""){
alert('请输入要查询的微信号！');
FrmSearch.UNumber.focus();
return false;
}

return true;

}
</script>
    
<style>

.bgpica {
	background-image: url(<?=$cf['bgpic']?>);
	background-repeat: no-repeat;
	color:#ffffff;
 }
 a {color:#ffffff;}
@media screen and (max-width: 800px) { 
.table {
	width: 300px;
}
.bgpica {
	background-image: url(<?=$cf['mbgpic']?>);
	background-repeat: no-repeat;
	color:#ffffff;
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
<body class="bgpica" >

 <div class="logobox">
<img src="<?=$cf['agent_logo']?>" width="100%"/></div>
 <div>
        <form name="searchform" id="searchform" method="get" action="search2.php?act=query" onSubmit="return CheckSearchForm();">
          <input type="hidden" name="act" id="act" value="query">
           <table width="100%" border="0">
           <tr><td align="center"><span style="font-family:黑体;color:#ffffff;font-size:15px;">
           
           [<label>微信<input name="show" value="weixin" type="radio" checked="checked" /></label>
           ]
           
           [<label>手机号<input name="show" value="phone" type="radio" /></label>]
           
           </span></td></tr>
             <tr>
               <td><div  align="center">
                     <input type="text"  name="keyword" id="wx" class="inputbox" onBlur="if (value ==''){value='请输入查询信息'}" onFocus="if (value =='请输入查询信息'){value=''}" value="请输入查询信息" />
                     
                   
              
                        
                     <input class="btn" onmouseover="this.className='btn_over'" onmouseout="this.className='btn'" type="submit" value="立即查询" style="vertical-align: middle;" />
                     <INPUT value='<?=$cf['yzm_status']?>' type='hidden' name='yzm_status' id='yzm_status'>
                 </div></td>
             </tr>
           </table>
</form>

 <div align="center" class="nrboxx">
<?php echo $result_str;?></div>
 <div align="center" style="margin-top:100px;font-size:15px;">  <?=$cf['copyrighta']?></div>
</div>


</body>
</html>
                
				

