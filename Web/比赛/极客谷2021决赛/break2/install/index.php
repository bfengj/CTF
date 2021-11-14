<?php
//检测安装文件是否存在
if (file_exists("install.lock"))
{
echo "对不起，您的程序已经安装过。请修改install.lock为install.php再浏览页面";
exit;
}
?>
<HTML>
<HEAD>
<TITLE>微应用网络安装向导-微营销代理查询+防伪系统</TITLE>
<meta name="keywords"       content="微应用网络安装向导"    >
<META  name="description"   content="微应用网络安装向导" >
<META content="MSHTML 6.00.2800.1170" name=GENERATOR>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body,td{
        word-break:break-all;
        color:#000000;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-size: 12px;
        line-height:18px
}

a:link{color:#333333;
	text-decoration: underline;
}
a:visited {color:#333333;
	text-decoration: underline;
}
a:hover {color:#333333;
	 text-decoration:none;
}

.button{
border-top-width:1;
border-bottom-width:1;
border-left-width:1;
border-right-width:1;
border-style:solid;
border-color:#98A9CF;
height:22;
background-color: #ffffff;
cursor:hand
}
.tdbg{
	background-color:#666666;
        color:#ffffff;
     }  

</style>
</HEAD>

<form name="ctl00" method="post" action="check.php" id="ctl00" topmargin="0" leftmargin="0">

<table  border="0" cellpadding="0" cellspacing="0" width="100%" align=center>
  <tr>
   <td height=50  bgcolor=#0066CC><img src="instyle/img/logo.gif" width=300 height=50></td>
  </tr>
 <tr>
   <td height=20 bgcolor=#0066CC><td>
 </tr>
</table>


<table border=0 cellpadding=0 cellspacing=0 width=100%>
 <tr>
   <td height=20  bgcolor="#98A9CF" align=center>步骤1：用户须知<td>
 </tr>
</table>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
 <tr><td align=center>
<textarea cols="120" rows="28" style="border-top-width:0;border-bottom-width:1;border-left-width:1;border-right-width:1;border-style:solid;border-color:#98A9CF">
微营销网站管理系统最终用户授权协议 

    感谢您使用微营销代理管理+防伪查询系统(以下简称微营销)，微营销是一款基于微软PHP平台开发，集成代理查询、证书授权、多等级代理管理、防伪查询等功能于一体的企业级网站管理系统。
    微应用为微营销产品的开发商，依法独立拥有微营销产品著作权。 
    无论个人、企业或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用微营销软件。 
    本授权协议适用于微营销软件的所有版本，微营销官方拥有对本授权协议的最终解释权。

    一、协议许可的权利 
    1. 您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。
    2. 获得商业授权之后，您有权删除、更改网站前台微营销的版权信息，同时拥有特定技术支持期限、技术支持方式和技术支持内容。

    二、协议规定的约束和限制
    1. 未经我司书面许可，不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放授权号。
    2. 未经我司书面许可，禁止在微营销的整体或任何部分基础上以发展任何派生版本、修改版权或第三方版本用于重新分发。
    3. 如果您未能遵守本协议的条款，我司有随时勒令用户终止使用的权利，并保留进一步追究相关法律责任的权力。

    三、有限担保和免责声明 
    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。
    2. 用户出于自愿而使用本系统，您必须了解使用本系统的风险，在未获取商业授权之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本系统而产生问题的相关责任。
    3. 电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始安装微营销，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关条款的约束和限制。
 </textarea>

</td>
</tr>

<tr>
<td colspan=2 align=center height=40px>
<input type="submit" name="ctl01" value="安装微营销管理系统" onClick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl01&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, false))" class="button" />&nbsp;&nbsp;

</td>
</tr>
</table>














<div align=left style="padding-left:100px"><span id="Lbl_error" style="color:#ff0000;font-size:13px"></span></div>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
  <tr>
   <td height=20 background="instyle/images/img/bg_1.gif" align="center"></td>
 </tr>
</table>
</form>

</body>
</html>