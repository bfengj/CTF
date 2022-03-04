<?php
/**
*	模块：daily.工作日报统计
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var atype=params.atype,columna=[];
	var column = [{
		text:'部门',dataIndex:'deptname',align:'left',sortable:true
	},{
		text:'姓名',dataIndex:'name',sortable:true
	},{
		text:'应写',dataIndex:'totaly'
	},{
		text:'已写',dataIndex:'totalx'
	},{
		text:'未写',dataIndex:'totalw'
	}];
	for(var i=1;i<=28;i++){
		columna.push({
			text:''+i+'',
			dataIndex:'day'+i+'',
		});
	}
	var a = $('#view_{rand}').bootstable({
		tablename:'daily',fanye:true,params:{'atype':atype},url:publicmodeurl('daily'),storeafteraction:'anxyfxaftershow',storebeforeaction:'anxyfxbeforershow',
		columns:[].concat(column,columna),
		itemclick:function(){
			
		},
		loadbefore:function(d){
			var cs = [],i;
			for(i in column)cs.push(column[i]);
			var warr=['日','一','二','三','四','五','六'],w=parseFloat(d.week),tsa;
			for(i=1;i<=d.maxjg;i++){
				if(i>1)w++;
				if(w>6)w=0;
				tsa = ''+i+'';
				if(w==0||w==6)tsa='<font color="#ff6600">'+tsa+'</font>';
				cs.push({
					text:tsa,
					dataIndex:'day'+i+'',
				});
			}
			a.setColumns(cs);
		}
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			var is = (get('isdaily_{rand}').checked)?'1':'0';
			a.setparams({key:s,dt1:get('dt1_{rand}').value,isdaily:is},true);
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
			js.msg('wait','['+dt+']月份的日报统计中...');
			js.ajax(publicmodeurl('daily','dailyfx'),{dt:dt,atype:atype},function(){
				js.msg('success','统计成功');
				a.reload();
			});
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'日报统计('+get('dt1_{rand}').value+')',
				'btnobj':o1,
				'notdingyue':true
			});
		}
	};
	
	$('#dt1_{rand}').val(js.now('Y-m'));
	js.initbtn(c);
});
</script>
<div>
<table width="100%"><tr>
	<td nowrap>月份&nbsp;</td>
	<td nowrap>
		<div style="width:120px"  class="input-group">
			<input placeholder="月份" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:150px" id="key_{rand}"   placeholder="姓名/部门">
	</td>
	<td nowrap style="padding-left:10px">
		<label><input id="isdaily_{rand}" checked type="checkbox">只看需写日报</label>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="daochu" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
	<td  style="padding-left:5px">
		
	</td>
	<td width="80%"></td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="anaynow" type="button">重新统计</button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">如全天请假视为不需要写日报，×未写,√已写,◇写周报,假:全天请假,空白(休息日)</div>