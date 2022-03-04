<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var lbefe = false;
	var at = $('#optionview_{rand}').bootstree({
		url:js.getajaxurl('gettreedata','option','system',{'num':'goodstype'}),
		columns:[{
			text:'物品分类',dataIndex:'name',align:'left',xtype:'treecolumn',width:'79%'
		},{
			text:'ID',dataIndex:'id',width:'20%'
		}],
		load:function(d){
			c.loadfile('0','所有物品');
		},
		itemdblclick:function(d){
			c.loadfile(d.id,d.name);
		}
	});
	
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
		text:'总库存',dataIndex:'stock',sortable:true
	}];
	
	var a = $('#view_{rand}').bootstable({
		tablename:'goods',celleditor:true,fanye:true,modenum:'goods',autoLoad:false,
		url:publicstore('{mode}','{dir}'),params:{'atype':'pan'},storebeforeaction:'beforeshow',storeafteraction:'aftershow',
		columns:columns,
		loadbefore:function(d){
			if(!lbefe && d.depotarr){
				for(var i=0;i<d.depotarr.length;i++)columns.push({text:d.depotarr[i].namea,dataIndex:'stockdepotid'+d.depotarr[i].id+''});
				a.setColumns(columns);
			}
			lbefe = true;
		},
		itemdblclick:function(d){
			openxiangs('【'+d.name+'】详情','goods',d.id);
		}
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt:get('dt1_{rand}').value},true);
		},

		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'物品盘点',
				'btnobj':o1
			});
		},
		loadfile:function(spd,nsd){
			$('#megss{rand}').html(nsd);
			a.setparams({'typeid':spd}, true);
		},
		genmu:function(){
			this.loadfile('0','所有物品');
		},
		del:function(){
			a.del({checked:true,url:js.getajaxurl('delxiang','{mode}','{dir}')});
		}
	};
	

	js.initbtn(c);
	
	$('#optionview_{rand}').css('height',''+(viewheight-25)+'px');
});
</script>

<table width="100%">
<tr valign="top">
<td width="220">
	<div style="border:1px #cccccc solid;width:220px">
	  <div id="optionview_{rand}" style="height:400px;overflow:auto;"></div>
	</div>  
</td>
<td width="10" nowrap>&nbsp;</td>
<td>


<div>
<table width="100%"><tr>
	<td align="left" nowrap>
			<button class="btn btn-default" click="genmu"  type="button">所有物品</button>&nbsp; 
	</td>
	<td nowrap  style="padding-left:10px">
		<input placeholder="日期" style="width:120px" onclick="js.changedate(this)" readonly class="form-control datesss" id="dt1_{rand}" >
	</td>

	<td style="padding-left:10px">
	<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="物品名/型号/规格">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	
	<td width="90%">
		&nbsp;&nbsp;<span id="megss{rand}"></span>
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>

</td>
</tr>
</table>