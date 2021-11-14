<?php
error_reporting(0);
header('Content-type: text/html; charset=utf-8');
require("data/head.php");

 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>代理商注册</title>
<link href="style/css/xfl_css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/areaid.js"></script>
<script type="text/javascript" src="style/js/js.common.js"></script>
</head>

<body>
<div class="zhuce_head"><img src="<?=$cf['site_logo']?>" /></div>
<div class="zhuce_main">
  <div class="zhuce_maintl">请填写以下注册项目</div>


      <form name="formagent" method="post" enctype="multipart/form-data" action="admin/sqagent.php?act=Addagent" id="messform">
     
             <input type="hidden" name="addtime" value="<?php echo date('Y-m-d',time());?>">

       <input  type="hidden" name="applytime" value="<?php echo date('Y-m-d h:s',time());?>">

		
<?php 
$time = time(); //当前时间戳
$datea = date('Y',$time) + 1 . '-' . date('m-d');//一年后日期
?>
           

          <input type="hidden" name="jietime" value="<?php echo $datea;?>">
 <?php
$dingdanhao = date("i-s");
$dingdanhao = str_replace("-","",$dingdanhao);
$dingdanhao .= rand(1000,999999);
?>

          <input name="agentid" type="hidden" id="agentid" size="50" value="<?=$cf['agentqz']?><?php echo $dingdanhao ?>">
	<table border="0" cellspacing="0" cellpadding="0" class="tableclas1"  style="font-family:'微软雅黑'; font-size:16px;">
    <tr>
			<td class="leftweidd" height="75" align="right">姓名</td>
			<td><input type="text" name="name" id="name"  class="rs_input1" /></td>
		</tr>
        <tr>
			<td height="75" align="right">微信号</td>
			<td><input type="text" name="weixin" id="weixin"  class="rs_input1" /></td>
		</tr>
          <tr>
			<td height="75" align="right">登录密码</td>
			<td><input type="password" name="password" id="password"  class="rs_input1" /></td>
		</tr>
		<tr>
			<td  height="75" align="right">手机号</td>
			<td>
			  <input type="text" name="phone" id="phone" class="rs_input1" maxlength="11"/></td>
		</tr>
		
		<tr>
			<td height="75" align="right">身份证号</td>
			<td><input type="text" name="idcard" id="idcard"  class="rs_input1" /></td>
		</tr>
        
        	<tr>
			<td height="75" align="right">Q Q</td>
			<td><input type="text" name="qq" id="qq"  class="rs_input1" /></td>
		</tr>
        
        	<tr>
			<td height="75" align="right">上级微信</td>
			<td><input type="text" name="sjdl" id="sjdl"  class="rs_input1" /></td>
		</tr>
        <tr>
			<td height="30" align="right"></td>
			<td>（上级代理微信号，若无可不填）</td>
		</tr>
        
        	<tr>
			<td height="75" align="right">选择级别</td>
			<td><select class="ui-btn ui-icon-carat-d ui-btn-icon-right ui-corner-all ui-shadow" name="dengji" id="UserNote" style="width:93%">

                   <?php
foreach ($dengjiOpt as $key => $value) {
  echo '<option value="'.$value.'">'.$value.'</option>';
}
?>

					  

                    </select></td>
		</tr>
        
        	<tr>
			<td height="75" align="right">代理产品</td>
			<td><select class="ui-btn ui-icon-carat-d ui-btn-icon-right ui-corner-all ui-shadow" name="product" id="UserNote" style="width:93%">

                  <?php

		 $sql = "select * from tgs_product order by jibie DESC";

		 $res = mysql_query($sql);
		 
        
		 while($arr = mysql_fetch_array($res)){		
       
		?>

     <option value="<?php echo $arr["proname"];?>"><?php echo $arr["proname"];?></option>
        
        

		<?php

		}
		
		

	
		?>

					  

                    </select></td>
		</tr>
        
        	<tr>
			<td height="75" align="right">区域</td>
			<td><input type="text" name="quyu" id="quyu"  class="rs_input1" /></td>
		</tr>
        
        	<tr>
			<td height="75" align="right">备注</td>
			<td><input type="text" name="beizhu" id="beizhu"  class="rs_input11" /></td>
		</tr>
	
		

	
		
		



	
		
		
		
			<!--<tr>
			<td>&nbsp;</td>
			<td colspan="2"><div class="fwtk1">已同意<a href="#">《&middot;中国服务条款》</a></div></td>
			</tr>-->
		<tr>
			<td height="80">&nbsp;</td>
			<td>
				<input name="input" type="submit" value="填写完成并提交" class="tj_input" id="tijiao" />
			</td>
		</tr>
	</table>
</form>

</div>
</body>
</html>
