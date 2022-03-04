<?php if(!defined('HOST'))die('not access');?>

<script >
$(document).ready(function(){

	var a = $('#view_{rand}').bootstable({
		tablename:'goods',celleditor:true,fanye:true,modenum:'goods',autoLoad:false,
		url:publicstore('{mode}','{dir}'),params:{atype:'all'},modename:'物品',storebeforeaction:'beforeshow',storeafteraction:'aftershow',
		checked:true,
		columns:[{
			text:'编号',dataIndex:'num'
		},{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'分类',dataIndex:'typeid',align:'left'
		},{
			text:'单价',dataIndex:'price',sortable:true,editor:true
		},{
			text:'规格',dataIndex:'guige'
		},{
			text:'型号',dataIndex:'xinghao'
		},{
			text:'总库存',dataIndex:'stock',sortable:true
		},{
			text:'单位',dataIndex:'unit',sortable:true
		},{
			text:'',dataIndex:'caozuo'
		}],
		itemclick:function(){
			btn(false);
		},
		itemdblclick:function(d){
			openxiangs('物品 '+d.name,'goods',d.id);
		}
	});
	
	goodsrocks{rand} = function(s){
		a.reload();
	}
	var c = {
		del:function(){
			a.del({check:function(lx){if(lx=='yes')btn(true)}});
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		clickwin:function(o1,lx){
			var icon='plus',name='新增',id=0;
			if(lx==1){
				id = a.changeid;
			};
			openinput('物品产品','goods', id, 'goodsrocks{rand}');
		},
		piliang:function(){
			managelistgoods = a;
			addtabs({num:'daorugoods',url:'flow,input,daoru,modenum=goods',icons:'plus',name:'导入物品'});
		},
		rukuchu:function(o1, lx){
			var s='物品入库';
			if(lx==1)s='物品出库';
			addtabs({num:'rukuchugood'+lx+'',url:'main,goods,churuku,type='+lx+'',icons:'plus',name:s});
		},
		relaodkc:function(){
			js.ajax(js.getajaxurl('reloadkc','{mode}','{dir}'),{},function(){
				a.reload();
			},'get','','刷新中...,刷新完成');
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'物品列表',
				'modenum':'goods',
				'btnobj':o1
			});
		},
		prinwem:function(){
			var sid = a.getchecked();
			if(sid==''){
				js.msg('msg','没有选中记录');
				return;
			}
			var url = '?a=printewm&m=goods&d=main&sid='+sid+'';
			window.open(url);
		},
		
		
		mobj:a,
		title:'物品分类',
		stable:'goods',
		optionview:'optionview_{rand}',
		optionnum:'goodstype',
		rand:'{rand}'
	};
	
	var c = new optionclass(c);
	
	function btn(bo){
		//get('del_{rand}').disabled = bo;
		//get('edit_{rand}').disabled = bo;
	}
	
	js.initbtn(c);
	
});
</script>
<table width="100%">
<tr valign="top">
<td>
	<div style="border:1px #cccccc solid;width:220px">
	<div id="optionview_{rand}" style="height:400px;overflow:auto;"></div>
	</div>  
</td>
<td width="10" nowrap>&nbsp;</td>
<td width="95%">


<div>
<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
	</td>
	
	<td style="padding-left:10px">
	<input class="form-control" style="width:150px" id="key_{rand}"   placeholder="物品名">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td  width="80%" style="padding-left:10px">
		<div class="btn-group">
		<button class="btn btn-default" click="rukuchu,0" type="button">入库</button>
		<button class="btn btn-default" click="rukuchu,1" type="button">出库</button>
		</div>
	</td>
	<td  style="padding-right:10px">
		<button class="btn btn-default" click="piliang" type="button">导入</button>
	</td>
	<td  style="padding-right:10px">
		<button class="btn btn-default" click="daochu" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default"  click="prinwem" type="button">打印二维码</button>&nbsp;
		<button class="btn btn-default" click="relaodkc" type="button">刷新库存</button>
		
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">在出入库详情需要已审核才会计算库存的，表goodss上字段status=1时。</div>

</td>
</tr>
</table>