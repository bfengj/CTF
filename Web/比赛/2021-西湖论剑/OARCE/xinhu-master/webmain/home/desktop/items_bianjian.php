<?php 
/**
*	桌面首页项(便笺)
*/
defined('HOST') or die ('not access');

?>

<div align="left" style="min-width:300px" class="list-group">
<div class="list-group-item list-group-item-danger">
	<i class="icon-edit"></i> <?=$itemnowname?>
	<button type="btn" onclick="openinput('便笺','bianjian','','homebsbianjback')" class="btn btn-default btn-xs"><i class="icon-plus"></i> 新增</button>
	<a style="float:right;TEXT-DECORATION:none" onclick="morebianjian()">更多&gt;&gt;</a>
</div>

 <div class="list-group-item" style="padding:0">
	<table width="100%">
	<tr valign="top">
	<td><div style="width:350px;height:290px" id="bianjian_view"></div></td>
	<td width="100%">
	<div align="center" style="line-height:40px;background:#f1f1f1"><span id="bianjianmonth"></span></div>
	<div style="padding:5px" id="bianqianlists"></div>
	<div style="color:#cccccc;margin-top:40px" id="bianqianlists22" align="center">无便笺内容</div>
	
	</td>
	</tr>
	</table>

  </div>
</div>


<script>
morebianjian=function(){
	addtabs({num:'bianjian',url:'flow,page,bianjian,atype=my',icons:'edit',name:'我的便笺'});
}
homeobject.show_bianjian_list=function(a){
	
}

homeobject.bianjian_init=function(){
	
	this.shownowdstr=function(dt1){
		if(!this.bianjiandatass)return;
		
		var ndts = this.bianjiandatass[dt1];
		var str = '';
		if(ndts)for(var i=0;i<ndts.length;i++){
			str+='<div style="padding:5px 0px">['+ndts[i].time+']'+ndts[i].state+''+ndts[i].content+'</div>';
		}
		$('#bianqianlists').html(str);
		$('#bianqianlists22').show();
		if(str)$('#bianqianlists22').hide();
	}
	
	this.bianjianobjmb = $('#bianjian_view').rockcalendar({
		height:280,
		changemonth:function(y, m,o1){
			$('#bianjianmonth').html('<font onclick="homeobject.bianjianobjmb.fanmonth(-1)"><i class="icon-double-angle-left"></i></font> <font onclick="homeobject.bianjianobjmb.nowmonth()">'+y+'年'+xy10(m)+'月</font> <font  onclick="homeobject.bianjianobjmb.fanmonth(1)"><i class="icon-double-angle-right"></i></font>');
			homeobject.bianjiangetdata(o1.dayobj[1].day,o1.dayobj[42].day);
		},
		renderer:function(day, s, s1,s2,col1,col2){
			var s = '<div align="center" style="line-height:16px"><font color='+col1+'>'+s1+'</font><br><font temp="bianjian_'+day+'" color="red" style="font-size:10px;display:none">●</font></div>';
			return s;
		},
		onclick:function(gY,gm,d){
			var ndt = ''+gY+'-'+xy10(gm)+'-'+xy10(d)+'';
			homeobject.nowtime = ndt;
			homeobject.shownowdstr(ndt);
		}
	});
	
}
homeobject.bianjiangetdata=function(st1,st2){
	js.ajaxbool = false;
	js.ajax(publicmodeurl('bianjian','homedata'),{st1:st1,st2:st2},function(ret){
		homeobject.bianjiangetshowdata(ret.rows);
	},'get,json');
}
homeobject.bianjiangetshowdata=function(da){
	this.bianjiandatass = da;
	for(var d in da){
		$('font[temp="bianjian_'+d+'"]').show();
	}
	this.shownowdstr(this.nowtime);
}

homebsbianjback=function(){
	homeobject.bianjianobjmb.nowmonth();
}
</script>