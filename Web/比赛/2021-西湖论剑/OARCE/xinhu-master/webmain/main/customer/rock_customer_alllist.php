<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	var a = $('#view_{rand}').bootstable({
		tablename:'customer',fanye:true,modenum:'customer',params:{'atype':atype},
		columns:[{
			text:'类型',dataIndex:'type'
		},{
			text:'名称',dataIndex:'name'
		},{
			text:'单位名称',dataIndex:'unitname'
		},{
			text:'拥有者',dataIndex:'optname'
		},{
			text:'合同数',dataIndex:'htshu',sortable:true
		},{
			text:'销售总额',dataIndex:'moneyz',sortable:true
		},{
			text:'待收金额',dataIndex:'moneyd',sortable:true
		},{
			text:'创建时间',dataIndex:'adddt',sortable:true
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
	
	function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}
	
	var c = {
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs('客户','customer',d.id);
		},
		daochu:function(){
			a.exceldown();
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		retotal:function(){
			js.ajax(js.getajaxurl('retotal','{mode}','{dir}'),{},function(s){
				a.reload();
			},'get',false,'统计中...,统计完成')
		}
	};
	js.initbtn(c);
	

});
</script>
<div>
	<table width="100%">
	<tr>
	<td>
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="名称/拥有者">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="retotal" type="button">重新统计</button> 
	</td>
	<td  width="90%" style="padding-left:10px">
		
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
<div class="tishi">统计并不是实时统计，数据有偏差?请点[重新统计]按钮。销售总额是从收款单上统计。</div>
