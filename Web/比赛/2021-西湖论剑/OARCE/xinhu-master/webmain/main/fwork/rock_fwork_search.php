<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var c={
		showdata:function(a){
			var hhu = parseInt(viewwidth/260);
			var j=0,lx,d,s1,i,l=0,len;
			var strarr = [];for(i=0;i<hhu;i++)strarr[i]='';
			for(lx in a){
				d=a[lx];s1='';j++;len=d.length;
				s1 ='<div align="left" style="margin:20px;width:230px" class="list-group">';
				s1+='<div class="list-group-item  list-group-item-success"><i class="icon-star-empty"></i> '+lx+'('+len+')</div>';
				for(i=0;i<len;i++){
					s1+='<a style="TEXT-DECORATION:none" onclick="opencoluske_{rand}(\''+d[i].name+'\',\''+d[i].num+'\',\''+d[i].atype+'\')" class="list-group-item">'+d[i].name+'</a>';
				}
				s1+='</div>';
				strarr[l]+=s1;
				l++;
				if(l==hhu)l=0;
			}
			var s='<table><tr valign="top">';
			for(i=0;i<hhu;i++)s+='<td>'+strarr[i]+'</td>';
			s+='</tr></table>';
			$('#view_{rand}').html(s);
		}
	}
	
	opencoluske_{rand}=function(na,num,ats){
		if(!ats)ats='my';
		addtabs({name:na,num:'search'+num+'',url:'flow,page,'+num+',atype='+ats+'',icons:'search'});
	}
	
	js.ajax(js.getajaxurl('getmodesearcharr','{mode}','{dir}'),{},function(a){
		c.showdata(a.rows);
	},'get,json');
});
</script>

<div id="view_{rand}"></div>

