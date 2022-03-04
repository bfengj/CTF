<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	var obj=[];
	obj[0] = $('#view0_{rand}').bootstable({
		tablename:'custsale',params:{'atype':'mygx'},fanye:false,modenum:'custsale',modename:'销售机会',limit:5,moreurl:'flow,page,custsale,atype=mygx',morenum:'num110',
		columns:[{
			text:'客户',dataIndex:'custname',align:'left'
		},{
			text:'来源',dataIndex:'laiyuan'
		},{
			text:'状态',dataIndex:'state'
		},{
			text:'金额',dataIndex:'money'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		}],
		itemdblclick:function(d){
			openxiangs(this.modename,this.modenum,d.id);
		}
	});
	
	obj[1] = $('#view1_{rand}').bootstable({
		tablename:'custract',params:{'atype':'my'},fanye:false,modenum:'custract',modename:'合同',limit:5,moreurl:'flow,page,custract,atype=my',morenum:'num108',
		columns:[{
			text:'合同编号',dataIndex:'num'
		},{
			text:'客户',dataIndex:'custname',align:'left'
		},{
			text:'签约日期',dataIndex:'signdt',sortable:true
		},{
			text:'合同金额',dataIndex:'money',sortable:true
		},{
			text:'待收付款',dataIndex:'moneys',sortable:true
		},{
			text:'状态',dataIndex:'statetext'
		}],
		itemdblclick:function(d){
			openxiangs(this.modename,this.modenum,d.id);
		}
	});
	
	obj[2] = $('#view2_{rand}').bootstable({
		tablename:'custfina',params:{'atype':'myskdws'},modenum:'custfina',modename:'收款单',limit:5,moreurl:'flow,page,custfina,atype=myskdws',morenum:'num106',
		columns:[{
			text:'所属日期',dataIndex:'dt'
		},{
			text:'合同编号',dataIndex:'htnum'
		},{
			text:'客户',dataIndex:'custname',align:'left'
		},{
			text:'金额',dataIndex:'money',sortable:true
		},{
			text:'状态',dataIndex:'ispay'
		}],
		itemdblclick:function(d){
			openxiangs(this.modename,this.modenum,d.id);
		}
	});
	
	obj[3] = $('#view3_{rand}').bootstable({
		tablename:'custfina',params:{'atype':'myfkdwf'},modenum:'custfina',modename:'付款单',limit:5,moreurl:'flow,page,custfina,atype=myfkdwf,pnum=fkd',morenum:'num107',
		columns:[{
			text:'所属日期',dataIndex:'dt'
		},{
			text:'合同编号',dataIndex:'htnum'
		},{
			text:'客户',dataIndex:'custname',align:'left'
		},{
			text:'金额',dataIndex:'money',sortable:true
		},{
			text:'状态',dataIndex:'ispay'
		}],
		itemdblclick:function(d){
			openxiangs(this.modename,this.modenum,d.id);
		}
	});
	
	obj[4] = $('#view4_{rand}').bootstable({
		tablename:'goodm',params:{'atype':'my'},modenum:'custxiao',modename:'销售单',limit:5,moreurl:'flow,page,custxiao,atype=my',morenum:'num300',statuschange:false,
		columns:[{
			text:'销售日期',dataIndex:'applydt'
		},{
			text:'销售单号',dataIndex:'num'
		},{
			text:'客户',dataIndex:'custname',align:'left'
		},{
			text:'金额',dataIndex:'money',sortable:true
		},{
			text:'状态',dataIndex:'statustext'
		}],
		itemdblclick:function(d){
			openxiangs(this.modename,this.modenum,d.id);
		}
	});
	
	var c = {
		reload:function(o1,lx){
			obj[lx].reload();
		},
		more:function(o1,lx){
			var d = obj[lx].options;
			addtabs({num:d.morenum,name:'我的'+d.modename+'',url:d.moreurl});
		}
	}
	js.initbtn(c);
});
</script>

<div align="left" style="padding:10px">
	<table  border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr valign="top">
		
		<td width="50%">
			<div align="left" style="min-width:300px" class="list-group">
			<div class="list-group-item  list-group-item-info">
				<i class="icon-flag"></i> 需跟踪销售机会
				<span style="float:right" ><a click="reload,0"><i class="icon-refresh"></i></a>&nbsp;<a click="more,0">更多&gt;&gt;</a></span>
			</div>
			<div id="view0_{rand}"></div>
			</div>
			
			<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-success">
				<i class="icon-flag"></i> 合同
				<span style="float:right" ><a click="reload,1"><i class="icon-refresh"></i></a>&nbsp;<a click="more,1">更多&gt;&gt;</a></span>
			</div>
			<div id="view1_{rand}"></div>
			</div>
			
			
		
			
		</td>
		
		<td style="padding-left:20px;">
			
			<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-info">
				<i class="icon-money"></i> 销售单
				<span style="float:right" ><a click="reload,4"><i class="icon-refresh"></i></a>&nbsp;<a click="more,4">更多&gt;&gt;</a></span>
			</div>
			<div id="view4_{rand}"></div>
			</div>
		
			<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-success">
				<i class="icon-money"></i> 待收款单
				<span style="float:right" ><a click="reload,2"><i class="icon-refresh"></i></a>&nbsp;<a click="more,2">更多&gt;&gt;</a></span>
			</div>
			<div id="view2_{rand}"></div>
			</div>
			
			<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-danger">
				<i class="icon-money"></i> 待付款单
				<span style="float:right" ><a click="reload,3"><i class="icon-refresh"></i></a>&nbsp;<a click="more,3">更多&gt;&gt;</a></span>
			</div>
			<div id="view3_{rand}"></div>
			</div>
			
		</td>
		
		
		
	</tr>
	</table>
	<div class="tishi">双击对应记录可查看详情！</div>
</div>
