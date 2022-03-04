<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var modenum='kqerr';
	var atype=params.atype
	var a = $('#view_{rand}').bootstable({
		tablename:'kqerr',params:{'atype':atype},fanye:true,modenum:modenum,statuschange:false,
		columns:[{
			text:'部门',dataIndex:'deptname'
		},{
			text:'姓名',dataIndex:'name'
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'异常类型',dataIndex:'errtype',sortable:true
		},{
			text:'异常日期',dataIndex:'dt',sortable:true
		},{
			text:'应打卡时间',dataIndex:'ytime'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
			text:'状态',dataIndex:'statustext'
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		},
		itemdblclick:function(d){
			openxiangs('打卡异常',modenum,d.id);
		}
	});
	
	function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}

	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,'month':get('dt1_{rand}').value},true);
		},
		daochu:function(){
			a.exceldown();
		},
		view:function(){
			var d=a.changedata;
			openxiangs('打卡异常',modenum,d.id);
		}
	};
	js.initbtn(c);
});
</script>
<div>
<table width="100%">
<tr>
	<td nowrap>
		<div style="width:140px"  class="input-group">
			<input placeholder="月份" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="return js.selectdate(this,'dt1_{rand}','month')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:250px" id="key_{rand}"   placeholder="人员/部门">
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  style="padding-left:10px" width="90%">
	
	
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp; 
		<button class="btn btn-default" click="daochu,1" type="button">导出</button> 
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
