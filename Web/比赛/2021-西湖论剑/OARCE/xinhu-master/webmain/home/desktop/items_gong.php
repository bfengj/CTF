<?php 
/**
*	桌面首页项(通知公告)
*/
defined('HOST') or die ('not access');

?>
<script>
moregonggao=function(){
	addtabs({num:'gong',url:'flow,page,gong,atype=my',icons:'volume-up',name:'通知公告'});
}
homeobject.show_gong_list=function(a){
	var s='',a1,i,col;
	$('#homegonglist a[temp]').remove();
	for(i=0;i<a.length;i++){
		a1=a[i];
		col=(a1.ishui==1)?'#aaaaaa':'';
		s+='<a temp="list" style="color:'+col+';TEXT-DECORATION:none" onclick="openxiangs(\''+a1.typename+'\',\'gong\',\''+a1.id+'\');" class="list-group-item">◇【'+a1.typename+'】'+a1.title+'('+a1.indate+')</a>';
	}
	$('#homegonglist').append(s);
}
</script>
<div align="left" id="homegonglist" style="min-width:300px" class="list-group">
<div class="list-group-item  list-group-item-info">
	<i class="icon-volume-up"></i> <?=$itemnowname?>
	<a style="float:right;TEXT-DECORATION:none" onclick="moregonggao()">更多&gt;&gt;</a>
</div>
</div>