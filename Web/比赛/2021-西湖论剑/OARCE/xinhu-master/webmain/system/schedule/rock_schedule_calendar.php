<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){

	var month;

	var c = {
		change:function(o1, lx){
			mobj.fanmonth(lx);
		},
		nowchange:function(){
			mobj.nowmonth();
		},
		plusadd:function(){
			openinput('日程','schedule','','wfhoew{rand}');
		},
		loadschedule:function(){
			$.get(js.getajaxurl('loadschedule','{mode}','{dir}', {month:month}), function(da){
				for(var d1 in da){
					var s='',s1,d=da[d1],i;
					for(i=0;i<d.length;i++){
					s+='<div onclick="openreng_{rand}('+d[i].id+')" style="height:20px;line-height20px;overflow:hidden;cursor:pointer">'+(i+1)+'.['+d[i].time.substr(11,5)+']'+d[i].title+'</div>';
					}
					$('#s'+d1+'_{rand}').html('<div style="border-top:1px #eeeeee solid;margin-top:3px;">'+s+'</div>');
				}
			},'json');
		},
		guanli:function(){
			addtabs({num:'guanlieschedule',url:'{dir},{mode},guan',name:'日程管理'});
		},
		ricdaibn:function(){
			addtabs({num:'scheduld',url:'flow,page,scheduld,atype=my',name:'日程待办'});
		}
	};
	wfhoew{rand}=function(){
		c.loadschedule();
	}
	openreng_{rand}=function(id1){
		openxiangs('日程','schedule', id1);
	}
	var mobj = $('#veiw_{rand}').rockcalendar({
		height:viewheight-80,
		changemonth:function(y, m){
			$('#nowmonth_{rand}').html(''+y+'年'+xy10(m)+'月');
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
			<button type="button" click="guanli" class="btn btn-default"><i class="icon-cog"></i> 管理</button>&nbsp; 
			<button type="button" click="ricdaibn" class="btn btn-default">日程待办</button>
		</td>
		<td align="center" width="40%">
			<div id="nowmonth_{rand}" style="font-size:16px">2015年06月</div>
		</td>
		<td align="right" width="30%">
			<button type="button"  click="plusadd" class="btn btn-success"><i class="icon-plus"></i> 新增记录</button>&nbsp; 
			<button type="button"  click="change,1" class="btn btn-default">下个月 <i class="icon-caret-right"></i></button>
			</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
