<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'kqanay',fanye:true,
		url:publicstore('{mode}','{dir}'),storeafteraction:'kqanayaftershow',storebeforeaction:'kqanaybeforeshow',pageSize:16,
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left'
		},{
			text:'姓名',dataIndex:'name',sortable:true
		},{
			text:'日期',dataIndex:'dt',sortable:true
		},{
			text:'星期',dataIndex:'week'
		},{
			text:'是否工作日',dataIndex:'iswork',sortable:true
		},{
			text:'状态名称',dataIndex:'ztname'
		},{
			text:'状态值',dataIndex:'state',align:'left'
		}],
		itemclick:function(){
			get('xqkaoqb_{rand}').disabled=false;
		}
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			var is = (get('iswork_{rand}').checked)?'1':'0';
			var is1 = (get('iskq_{rand}').checked)?'1':'0';
			a.setparams({key:s,dt1:get('dt1_{rand}').value,dt2:get('dt2_{rand}').value,iswork:is,iskq:is1},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'month',inputid:'dt'+lx+'_{rand}'});
		},
		anaynow:function(){
			var dt = get('dt1_{rand}').value;
			if(dt==''){
				js.msg('msg','请选择月份');
				return;
			}
			
			js.ajax(js.getajaxurl('kqanayallinit','{mode}','{dir}'),{dt:dt},function(ret){
				var oi=0,zong=ret.maxpage,i,cans,dar=[];
				for(i=1;i<=zong;i++){
					dar.push(js.getajaxurl('kqanayallpage','{mode}','{dir}',{dt:dt,page:i}));
				}
				queue.addlist(dar,function(){a.reload();},'['+dt+']月份的考勤分析');
			},'get,json');
		},
		xqkaoqb:function(){
			var d=a.changedata;
			if(!d.name)return;
			addtabs({num:'adminkaoqin'+d.uid+'',url:'main,kaoqin,geren,uid='+d.uid+'',icons:'time',name:''+d.name+'的考勤'});
		},
		daochu:function(){
			a.exceldown();
		}
	};
	
	//$('#dt1_{rand}').val(js.now('Y-m'));
	js.initbtn(c);
});
</script>
<div>
<table width="100%"><tr>
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
	<td nowrap style="padding-left:10px">
		<label><input id="iswork_{rand}" checked type="checkbox">只看工作日</label>
	</td>
	<td nowrap style="padding-left:10px">
		<label><input id="iskq_{rand}" checked type="checkbox">只看需考勤</label>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td style="padding-left:5px">
		
	</td>
	<td width="80%"></td>
	<td align="right" nowrap>
		<button class="btn btn-info" click="xqkaoqb" disabled id="xqkaoqb_{rand}" type="button">详情考勤表</button>&nbsp;&nbsp;
		<button class="btn btn-default" click="anaynow" type="button">全部重新分析</button>&nbsp;&nbsp;
		<button class="btn btn-default" click="daochu" type="button">导出</button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
