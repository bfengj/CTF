<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var columns = [{
		text:'日期',dataIndex:'dt',width:'150px'
	},{
		text:'星期',dataIndex:'week',width:'100px'
	},{
		text:'',dataIndex:'cont',align:'left'
	}];
	var a = $('#view_{rand}').bootstable({
		tablename:'schedule',fanye:false,modedir:'{mode}:{dir}',storebeforeaction:'schedulemybefore',storeafteraction:'schedulemyafter',
		columns:columns,
		loadbefore:function(d){
			get('dt1_{rand}').value=d.startdt;
			get('dt2_{rand}').value=d.enddt;
		}
	});
	
	var c = {
		search:function(){
			a.setparams({
				'startdt':get('dt1_{rand}').value,
				'enddt':get('dt2_{rand}').value
			},true);
		},
		daochu:function(){
			a.exceldown();
		},
		clickwin:function(){
			openinput('日程','schedule',0,'opegs{rand}');
		},
		guanli:function(){
			addtabs({num:'guanlieschedule',url:'flow,page,schedule,atype=my',name:'日程管理'});
		},
		calendarshow:function(){
			addtabs({num:'guanlieschedulemonth',url:'{dir},{mode},calendar',name:'日程月视图'});
		},
		ricdaibn:function(){
			addtabs({num:'scheduld',url:'flow,page,scheduld,atype=my',name:'日程待办'});
		}
	};
	js.initbtn(c);
	opegs{rand}=function(){
		a.reload();
	}
});
</script>
<div>
<table width="100%">
<tr>
	<td nowrap style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>&nbsp; 
		<button type="button" click="guanli" class="btn btn-default"><i class="icon-cog"></i> 管理</button>
		&nbsp; 
		<button type="button" click="ricdaibn" class="btn btn-default">日程待办</button>
	</td>
	<td nowrap>日期&nbsp;</td>
	<td nowrap>
		<div style="width:140px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="return js.selectdate(this,'dt1_{rand}')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td nowrap>&nbsp;至&nbsp;</td>
	<td nowrap>
		<div style="width:140px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="return js.selectdate(this,'dt2_{rand}')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  style="padding-left:10px" width="90%">
	
	
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="calendarshow" type="button"><i class="icon-calendar"></i> 月视图</button>&nbsp; 
		<button class="btn btn-default" click="daochu,1" type="button">导出</button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">列表上显示的是：我创建的日程/提醒给我的</div>