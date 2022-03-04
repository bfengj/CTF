<?php 
/**
*	桌面首页项(系统日志)
*/
defined('HOST') or die ('not access');

?>
<script>
openxtrilises=function(){
	addtabs({num:'syslog',url:'system,cog,log',icons:'bookmark-empty',name:'日志查看'});
}
homeobject.show_syslog_list=function(a){
	var s='',a1,i,atr;
	$('#homesysloglist a[temp]').remove();
	for(i=0;i<a.length;i++){
		a1=a[i];
		atr='';
		if(a1.level=='2')atr='color:red';
		s+='<a temp="list" style="TEXT-DECORATION:none;'+atr+'" class="list-group-item">◇【'+a1.type+'】'+a1.remark+'('+a1.optdt+')</a>';
	}
	$('#homesysloglist').append(s);
}
</script>
<div align="left" id="homesysloglist" class="list-group">
<div class="list-group-item  list-group-item-default">
	<i class="icon-bookmark-empty"></i> <?=$itemnowname?>
	<a style="float:right;TEXT-DECORATION:none" onclick="openxtrilises()">更多&gt;&gt;</a>
</div>
</div>