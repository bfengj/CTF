<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'kqinfo',params:{'atype':'all'},celleditor:true,fanye:true,modedir:'{mode}:{dir}',
		storeafteraction:'kqinfoaftershow',storebeforeaction:'kqinfobeforeshow',
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left'
		},{
			text:'姓名',dataIndex:'name'
		},{
			text:'类型',dataIndex:'kind',sortable:true
		},{
			text:'请假类型',dataIndex:'qjkind'
		},{
			text:'开始时间',dataIndex:'stime',sortable:true
		},{
			text:'截止时间',dataIndex:'etime',sortable:true
		},{
			text:'时间(小时)',dataIndex:'totals',sortable:true
		},{
			text:'加班兑换',dataIndex:'jiatype'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'状态',dataIndex:'status'
		},{
			text:'操作人',dataIndex:'optname'
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
			text:'截止使用',dataIndex:'enddt',sortable:true,textmsg:'超过这个时间不能在使用',editor:true,editorbefore:function(d){
				return (d.kind=='加班' || d.kind.substr(0,2)=='增加');
			}
		},{
			text:'',dataIndex:'caozuo'
		}],
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
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'',
				'btnobj':o1
			});
		},
		clickwin:function(){
			openinput('考勤信息','leavehr');
		},
		addnianjia:function(){
			var dt = get('dt1_{rand}').value;
			if(isempt(dt)){js.msg('msg','请先选择日期从，直接选今日就可以');return;}
			js.confirm('一键添加年假：入职日期满整年才会添加，如2017-12-20入职，今年要到<?=date('Y')?>-12-20才会添加年假，如添加去年开始日期你可以选去年<?=(date('Y')-1)?>-12-31。', function(jg){
				if(jg=='yes')c.addnianjias();
			});
		},
		addnianjias:function(){
			js.msg('wait','处理中...');
			js.ajax(js.getajaxurl('addnianjia','{mode}','{dir}'),{dt:get('dt1_{rand}').value},function(s){
				js.msg('success', s);
				a.reload();
			});
		},
		updateenddt:function(bo){
			js.confirm('更新截止日期是当加班设置有效期或年假设置有效期就需要更新，未在规定截止时间内使用就清0', function(jg){
				if(jg=='yes')c.updateenddts();
			});
		},
		updateenddts:function(){
			js.msg('wait','处理中...');
			js.ajax(js.getajaxurl('updateenddt','{mode}','{dir}'),false,function(s){
				js.msg('success', s);
				a.reload();
			});
		},
		daoru:function(){
			managelistleavehr = a;
			addtabs({num:'daoruleavehr',url:'flow,input,daoru,modenum=leavehr',icons:'plus',name:'考勤信息'});
		}
	};
	

	js.initbtn(c);
});
</script>
<div>
<table width="100%"><tr>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
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
	<td  style="padding-left:10px">
		<input class="form-control" style="width:100px" id="keys_{rand}"   placeholder="类型">
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="addnianjia" type="button">一键添加年假</button>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="updateenddt" type="button">更新截止日期</button>
	</td>
	
	<td width="80%">
	
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daoru" type="button">导入</button>&nbsp;
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
