<?php 
/**
*	桌面首页项(公文查阅)
*/
defined('HOST') or die ('not access');

?>
<script>
moreofficic=function(){
	addtabs({num:'officic',url:'flow,page,officic,atype=my',icons:'suitcase',name:'公文查阅'});
}
homeobject.show_officic_list=function(a){
	var s='',a1,i,col;
	$('#homeofficiclist a[temp]').remove();
	for(i=0;i<a.length;i++){
		a1=a[i];
		col=(a1.ishui==1)?'#aaaaaa':'';
		s+='<a temp="list" style="color:'+col+'" onclick="openxiangs(\'公文查阅\',\'officic\',\''+a1.id+'\');" class="list-group-item">◇ ['+a1.num+'] '+a1.title+'</a>';
	}
	$('#homeofficiclist').append(s);
}
</script>
<div align="left" id="homeofficiclist" style="min-width:300px" class="list-group">
<div class="list-group-item  list-group-item-danger">
	<i class="icon-suitcase"></i> 公文查阅
	<a style="float:right" onclick="moreofficic();">更多&gt;&gt;</a>
</div>
</div>