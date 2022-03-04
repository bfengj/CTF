<?php 
/**
*	桌面首页项(会议)
*/
defined('HOST') or die ('not access');

?>
<script>
moremeets=function(){
	addtabs({num:'meet',url:'flow,page,meet,atype=today',name:'今日会议'});
}
homeobject.show_meet_list=function(a){
	var s='',a1,i;
	$('#homemeetlist a[temp]').remove();
	for(i=0;i<a.length;i++){
		a1=a[i];
		s+='<a temp="list" style="TEXT-DECORATION:none;" onclick="openxiangs(\'会议\',\'meet\',\''+a1.id+'\');" class="list-group-item">◇'+a1.title+'</a>';
	}
	$('#homemeetlist').append(s);
}
</script>
<div align="left"  id="homemeetlist" class="list-group">
<div class="list-group-item  list-group-item-success">
	<i class="icon-flag"></i> <?=$itemnowname?>
	<a style="float:right;TEXT-DECORATION:none" onclick="moremeets()">更多&gt;&gt;</a>
</div>

</div>