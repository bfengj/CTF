<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var at = $('#optionview_{rand}').bootstree({
		url:js.getajaxurl('gettreedata','option','system',{'num':'goodstype'}),
		columns:[{
			text:'物品分类',dataIndex:'name',align:'left',xtype:'treecolumn',width:'79%'
		},{
			text:'ID',dataIndex:'id',width:'20%'
		}],
		load:function(d){
			c.loadfile('0','所有出入库详情');
		},
		itemdblclick:function(d){
			c.loadfile(d.id,d.name);
		}
	});
	var forbo = false;
	var a = $('#view_{rand}').bootstable({
		tablename:'goodss',autoLoad:false,celleditor:true,fanye:true,dir:'desc',sort:'a.id',checked:true,
		url:publicstore('{mode}','{dir}'),storebeforeaction:'xiangbeforeshow',storeafteraction:'xiangaftershow',
		columns:[{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'规格',dataIndex:'guige'
		},{
			text:'型号',dataIndex:'xinghao'
		},{
			text:'分类',dataIndex:'typeid',align:'left'
		},{
			text:'类型',dataIndex:'kind',sortable:true
		},{
			text:'日期',dataIndex:'applydt',sortable:true
		},{
			text:'操作人',dataIndex:'optname'
		},{
			text:'数量',dataIndex:'count',sortable:true,align:'right'
		},{
			text:'对应仓库',dataIndex:'depotname'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'状态',dataIndex:'status'
		}],
		loadbefore:function(d){
			if(!forbo){
				var d1 = [];
				for(var i in d.tyeparr)d1.push({value:i,name:d.tyeparr[i]});
				js.setselectdata(get('type_{rand}'),d1,'value');
			}
			forbo = true;
		}
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt:get('dt1_{rand}').value},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'month',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'物品出入库详情',
				'btnobj':o1
			});
		},
		loadfile:function(spd,nsd){
			$('#megss{rand}').html(nsd);
			a.setparams({'typeid':spd}, true);
		},
		genmu:function(){
			this.loadfile('0','所有出入库详情');
		},
		del:function(){
			a.del({checked:true,url:js.getajaxurl('delxiang','{mode}','{dir}')});
		},
		changetype:function(o1){
			a.setparams({types:o1.value},true);
		}
	};
	

	js.initbtn(c);
	
	$('#optionview_{rand}').css('height',''+(viewheight-25)+'px');
	$('#type_{rand}').change(function(){
		c.changetype(this);
	});
});
</script>

<table width="100%">
<tr valign="top">
<td width="220">
	<div style="border:1px #cccccc solid">
	  <div id="optionview_{rand}" style="height:400px;overflow:auto;"></div>
	</div>  
</td>
<td width="10"></td>
<td>


<div>
<table width="100%"><tr>
	<td align="left" nowrap>
			<button class="btn btn-default" click="genmu"  type="button">所有详情</button>&nbsp; 
	</td>
	<td nowrap  style="padding-left:10px">
		<div style="width:120px"  class="input-group">
			<input placeholder="月份" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>

	<td style="padding-left:10px">
	<input class="form-control" style="width:200px" id="key_{rand}" placeholder="物品名/操作人/仓库">
	</td>
	<td>
	<select class="form-control" style="width:130px" id="type_{rand}"><option value="">所有类型</option></select>
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	
	<td width="90%">
		&nbsp;&nbsp;<span id="megss{rand}"></span>
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button>&nbsp;&nbsp;
		<button class="btn btn-danger" id="del_{rand}" click="del" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>

</td>
</tr>
</table>