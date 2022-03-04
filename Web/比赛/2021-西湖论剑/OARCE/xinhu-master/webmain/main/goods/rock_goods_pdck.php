<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	//根据仓库统计下面的物品数量
	var depotid = params.depotid;if(!depotid)depotid='';
	
	var columns = [{
			text:'编号',dataIndex:'num'
	},{
		text:'名称',dataIndex:'name',align:'left'
	},{
		text:'分类',dataIndex:'typeid',align:'left'
	},{
		text:'单价',dataIndex:'price',sortable:true
	},{
		text:'单位',dataIndex:'unit'
	},{
		text:'规格',dataIndex:'guige'
	},{
		text:'型号',dataIndex:'xinghao'
	},{
		text:'ID',dataIndex:'id',sortable:true
	},{
		text:'数量',dataIndex:'stock'
	}];
	
	var a = $('#view_{rand}').bootstable({
		tablename:'goodss',celleditor:true,fanye:true,
		url:publicstore('{mode}','{dir}'),params:{'depotid':depotid},storebeforeaction:'pdck_beforeshow',storeafteraction:'pdck_aftershow',
		columns:columns
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt:get('dt1_{rand}').value},true);
		},

		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'btnobj':o1
			});
		}
	};
	

	js.initbtn(c);
	
	
});
</script>
<div>
<table width="100%"><tr>
	
	<td align="left" nowrap>
		<input placeholder="截止日期" style="width:120px" onclick="js.changedate(this)" readonly class="form-control datesss" id="dt1_{rand}" >
	</td>

	<td style="padding-left:10px">
	<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="物品名/型号/规格">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	
	<td width="90%">
		
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>