<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	js.ajax(js.getajaxurl('sysinfo','{mode}','{dir}'),{},function(a){
		var s='<table>';
		for(var f in a.fields){
			s+='<tr><td align="right"><b>'+a.fields[f]+'</b>：&nbsp;</td><td>'+a.data[f]+'</td></tr>';
		}
		s+='</table>';
		$('#veiw_{rand}').html(s);
	},'get,json');
});
</script>



<div  style="padding:50px; line-height:34px">
	<div>以下配置信息，请到系统的配置文件下修改(webmain/webmainConfig.php)，<a href="?a=phpinfo" target="_blank">查看phpinfo</a></div>
	<div class="blank1"></div>
	<div id="veiw_{rand}"></div>
</div>