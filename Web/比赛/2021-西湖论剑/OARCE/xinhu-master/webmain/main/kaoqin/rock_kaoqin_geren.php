<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var month=params.month,uid=params.uid;
	if(!uid)uid=''+adminid;
	if(!month)month='';
	var c = {
		change:function(o1, lx){
			mobj.fanmonth(lx);
		},
		nowchange:function(){
			mobj.nowmonth();
		},
		loadschedule:function(){
			$.get(js.getajaxurl('getmyanaykq','{mode}','{dir}', {month:month,uid:uid}), function(da){
				js.msg();
				var d1,s='';
				for(d1 in da){
					s=da[d1];
					if(s!='')$('#s'+d1+'_{rand}').html('<div style="border-top:1px #eeeeee solid;margin-top:3px;">'+s+'</div>');
				}
				s='';var toarr = da['total'];
				for(d1 in toarr)s+='，'+d1+':'+toarr[d1]+'';
				if(s!='')s=s.substr(1);
				$('#total_{rand}').html(s);
			},'json');
		},
		anaygr:function(){
			js.msg('wait','分析中...');
			$.get(js.getajaxurl('reladanaymy','{mode}','{dir}', {month:month,uid:uid}), function(da){
				c.loadschedule();
			});
		},
		reload:function(){
			js.msg('wait','刷新中...');
			c.loadschedule();
		}
	};
	var mobj = $('#veiw_{rand}').rockcalendar({
		height:viewheight-105,
		month:month,
		changemonth:function(y, m){
			$('#nowmonth_{rand}').html(''+y+'年'+xy10(m)+'月的考勤表');
			month	= ''+y+'-'+xy10(m)+'';
			c.loadschedule();
		},
		renderer:function(dt, s, d){
			var v = s;
			v+='<div style="font-size:12px;" id="s'+dt+'_{rand}"></div>';
			return v;
		}
	});
	js.initbtn(c);
});
</script>
<div>
	<table width="100%">
	<tr>
		<td align="left" width="30%">
			<button type="button" click="change,-1" class="btn btn-default"><i class="icon-caret-left"></i> 上个月</button>&nbsp; 
			<button type="button" click="nowchange" class="btn btn-default"><i class="icon-calendar"></i> 当月</button>&nbsp; 
			<button type="button" click="reload" class="btn btn-default">刷新</button>
		</td>
		<td align="center" width="40%">
			<div id="nowmonth_{rand}" style="font-size:16px">2015年06月</div>
		</td>
		<td align="right" width="30%">
			<button type="button"  click="anaygr" class="btn btn-info">重新分析</button>&nbsp; 
			<button type="button"  click="change,1" class="btn btn-default">下个月 <i class="icon-caret-right"></i></button>
			</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div style="height:30px;line-height:30px;border-top:1px #dddddd solid">&nbsp;统计：<span id="total_{rand}"></span></div>
<div id="veiw_{rand}"></div>
