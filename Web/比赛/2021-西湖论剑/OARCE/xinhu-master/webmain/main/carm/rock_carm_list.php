<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var modenum='carm';
	var a = $('#view_{rand}').bootstable({
		tablename:modenum,celleditor:true,modenum:modenum,fanye:true,
		columns:[{
			text:'',dataIndex:'caozuo'
		},{
			text:'车牌号',dataIndex:'carnum'
		},{
			text:'车辆品牌',dataIndex:'carbrand'
		},{
			text:'型号',dataIndex:'carmode'
		},{
			text:'车辆类型',dataIndex:'cartype'
		},{
			text:'购买日期',dataIndex:'buydt',sortable:true
		},{
			text:'状态',dataIndex:'state'
		},{
			text:'公开',dataIndex:'ispublic'
		},{
			text:'强险到期',dataIndex:'qxenddt',sortable:true
		},{
			text:'行驶证到期',dataIndex:'xszenddt',sortable:true
		},{
			text:'商业险到期',dataIndex:'syxenddt',sortable:true
		},{
			text:'年审截到期',dataIndex:'nsenddt',sortable:true
		}]
	});

	var c = {
		reload:function(){
			at.reload();
		},
		del:function(){
			a.del();
		},
		daochu:function(){
			a.exceldown();
		},
		adds:function(){
			openinput('车辆管理',modenum);
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		}
	};
	js.initbtn(c);
});
</script>


<div>
<table width="100%"><tr>
	<td align="left" nowrap>
		<button class="btn btn-primary" click="adds"  type="button"><i class="icon-plus"></i> 新增</button>&nbsp; 
	</td>
	
	<td style="padding-left:10px">
		<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="车牌号">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td width="90%">
		
	</td>
	<td align="right">
		<button class="btn btn-default"  click="daochu" type="button">导出</button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
