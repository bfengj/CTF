<?php 
/**
*	桌面首页项(快捷入口)
*/
defined('HOST') or die ('not access');

?>
<script>
homeobject.showicons=function(a){
	if(a.length%2!=0)a.push({name:'none'});
	a.push({name:'刷新统计中...',icons:'refresh',num:'refresh',color:'#888888'});
	this.menuarr = a;
	var o = $('#kuailistdonw'),s='<table width="100%"><tr>',a1,oi=0,s1t='';
	for(var i=0; i<a.length-1;i++){
		oi++;
		s1t= '';
		a1 = a[i];
		if(oi%2!=0)s1t='style="border-right:1px #dddddd solid"';
		s+='<td width="50%" '+s1t+'>';
		if(a1.name!='none'){
			s+='	<a style="border-radius:0px;border:none;TEXT-DECORATION:none" onclick="opentabsshowshwo('+i+',this)" class="list-group-item"><font color="'+a1.color+'"><i class="icon-'+a1.icons+'"></i></font> &nbsp;'+a1.name+'<span badge="'+a[i].num+'" style="display:none;background:red" class="badge red"></span></a>';
		}else{
			s+='	<a style="border-radius:0px;border:none;" class="list-group-item">&nbsp;</a>';
		}
		s+='</td>';
		if(oi%2==0)s+='</tr><tr style="border-top:1px #dddddd solid">';
	}
	s+='</tr></table>';
	o.html(s);
}
opentabsshowshwo=function(oi,o1){
	var a = homeobject.menuarr[oi];
	if(a.num=='refresh'){
		homeobject.refresh();
	}else{
		var anum = {num:a.num,url:a.url,name:a.name,icons:a.icons,id:a.id};
		addtabs(anum);
	}
	return false;
}
</script>
<div class="panel panel-default">
	<div class="panel-heading">
		<div style="font-size:14px"><i class="icon-refresh"></i> <?=$itemnowname?>
		<a style="float:right;TEXT-DECORATION:none" click="refresh" id="refresh_text">刷新</a>
		</div>
	</div>
	<div id="kuailistdonw"></div>
</div>