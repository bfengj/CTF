<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	var a = $('#view_{rand}').bootstable({
		tablename:'kqinfo',params:{'atype':atype},celleditor:true,fanye:true,modedir:'{mode}:{dir}',
		storeafteraction:'kqinfoaftershow',storebeforeaction:'kqinfobeforeshow',
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left'
		},{
			text:'姓名',dataIndex:'name'
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
			text:'类型',dataIndex:'kind',sortable:true
		},{
			text:'请假类型',dataIndex:'qjkind'
		},{
			text:'开始时间',dataIndex:'stime',sortable:true
		},{
			text:'截止时间',dataIndex:'etime',sortable:true
		},{
			text:'时间',dataIndex:'totals',sortable:true
		},{
			text:'加班兑换',dataIndex:'jiatype'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'状态',dataIndex:'status'
		},{
			text:'操作人',dataIndex:'optname'
		},{
			text:'截止使用',dataIndex:'enddt',sortable:true
		},{
			text:'',dataIndex:'caozuo'
		}],
		load:function(d){
			$('#kqtong{rand}').html(d.totalstr);
		},
		itemdblclick:function(d){
			openxiangs(d.modename,d.modenum,d.id);
		}
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt1:get('dt1_{rand}').value,dt2:get('dt2_{rand}').value,keys:get('keys_{rand}').value},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'month',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(){
			a.exceldown();
		},
		clickwin:function(o1, lx){
			if(lx==0)openinput('请假条','leave');
			if(lx==1)openinput('加班单','jiaban');
		},
		xiashu:function(o1){
			if(atype=='my'){
				o1.value='我的考勤';
				atype = 'down';
				nowtabssettext('下属考勤信息');
			}else{
				o1.value='下属考勤';
				atype = 'my';
				nowtabssettext('我的考勤信息');
			}
			a.setparams({atype:atype}, true);
		}
	};
	
	if(atype=='my')$('#down_{rand}').show();
	js.initbtn(c);
});
</script>
<div>
<table width="100%"><tr>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,0" type="button">新增请假条</button>
	</td>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,1" type="button">新增加班单</button>
	</td>
	<td nowrap>日期从&nbsp;</td>
	<td nowrap>
		<input style="width:110px" onclick="js.changedate(this)" readonly class="form-control datesss" id="dt1_{rand}" >
	</td>
	<td nowrap>&nbsp;至&nbsp;</td>
	<td nowrap>
		<input style="width:110px" onclick="js.changedate(this)" readonly class="form-control datesss" id="dt2_{rand}" >
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:150px" id="key_{rand}"   placeholder="姓名/部门">
	</td>
	<td style="padding-left:10px">
		<input class="form-control" style="width:100px" id="keys_{rand}"   placeholder="类型">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	
	<td width="80%" style="padding-left:10px">
		<input class="btn btn-default" click="xiashu" id="down_{rand}" style="display:none" value="下属考勤" type="button">
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出</button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div>我考勤信息统计：<span id="kqtong{rand}"></span></div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
