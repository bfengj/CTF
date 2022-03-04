<?php 
/**
*	桌面首页项(我的申请)
*/
defined('HOST') or die ('not access');

?>
<script>
moemyapplylist=function(){
	addtabs({num:'applymy',url:'main,fwork,bill,atype=my',icons:'align-left',name:'我的申请'});
}
homeobject.show_apply_list=function(a){
	var s='',a1,i;
	$('#myapplylist a[temp]').remove();
	for(i=0;i<a.length;i++){
		a1=a[i];
		s+='<a style="TEXT-DECORATION:none" temp="list" onclick="openxiangs(\''+a1.modename+'\',\''+a1.modenum+'\',\''+a1.id+'\');" class="list-group-item">◇'+a1.cont+'</a>';
	}
	if(a1)$('#myapplylisttotal').html(a1.count);
	$('#myapplylist').append(s);
}
</script>
<div align="left" id="myapplylist" style="min-width:300px" class="list-group">
<div class="list-group-item  list-group-item-info">
	<i class="icon-align-left"></i> <?=$itemnowname?>(<span id="myapplylisttotal">0</span>)
	<a style="float:right;TEXT-DECORATION:none" onclick="moemyapplylist()">更多&gt;&gt;</a>
</div>
</div>