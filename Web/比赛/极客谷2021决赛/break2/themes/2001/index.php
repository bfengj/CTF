
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META content="IE=11.0000" http-equiv="X-UA-Compatible">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$cf['site_name']?></title>
<meta name="keywords" content="<?=$cf['page_keywords']?>" />
<meta name="description" content="<?=$cf['page_desc']?>" />
<SCRIPT type="text/javascript" src="data/js/ajax.js"></SCRIPT>


<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<link type="text/css" rel="stylesheet" href="style_ys/css/css.css" />

</head>
<body>


<div id="topNavWrapper">
<div id="topNav">
<div class="logo">
  <img src="<?=$cf['site_logo']?>"/> </div>
</div>

</div>



<div class="w10006">
  <div class="news-box">
	<div class="pro-header" style="margin:0px auto 10px auto"><a href="/">网站首页</a>&nbsp;·&nbsp;<a  href="#">防伪查询系统</a></div>
    <div class="agenttit"><h2><strong style="font-size:28px">防伪查询系统</strong></h2></div>
        <div align="center">
        <div class="agenta">
        <input name='bianhao' id="bianhao" type="text" class="search-keyword" maxlength="18" value="请输入防伪码" onfocus="if(this.value=='请输入防伪码'){this.value='';}"  onblur="if(this.value==''){this.value='请输入防伪码';}" />
		 <?php if($cf['yzm_status']==1){?>验证码：

   <input name='yzm' id='yzm' class="inputYzm" type="text" value="" maxlength="4">

   <img src="data/code.php" alt="验证码" title="点击刷新" class="code" onClick="window.location.reload()"/>&nbsp; <?php } ?>
        &nbsp;<input name="" type="submit" class="search-btn" id="ButOK" onClick="return GetQuery();" value="点击查询" /> <INPUT value='' type='hidden' name='search' id='search'> <INPUT value='<?=$cf['yzm_status']?>' type='hidden' name='yzm_status' id='yzm_status'>
        
          

          <br/></div>
            <br/><br />
            <br /><br /><br />
            <div class="showinfo" style="display:block; text-align:center">
             <div class="result" id="tgs_result_str"><?php echo $result_str;?></div
            
           
           	></div>
            <div class="news-box" style="margin:30px auto 0px auto">
   
      
          
  </div>
</div>

<div style="font-size:18px; line-height:10px; margin-top:20px; height:15px; border-bottom:#ccc 0px solid; color:#333; font-family:Arial, Helvetica, sans-serif,'微软雅黑'">
<h2>&nbsp;</h2>
</div>
</div>
  </div>
<!--首页网页底部-->
<div class="foot">
    <div class="footera">
     <?=$cf['copyrighta']?>
	</div>
</div>
</body>



</html>
