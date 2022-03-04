<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var modenum='carmrese';
	var a = $('#view_{rand}').bootstable({
		tablename:'carmrese',celleditor:true,fanye:true,modenum:modenum,statuschange:false,
		columns:[{
			text:'使用车辆',dataIndex:'carnum'
		},{
			text:'申请日期',dataIndex:'applydt',sortable:true
		},{
			text:'使用者',dataIndex:'usename'
		},{
			text:'使用时间',dataIndex:'startdt',sortable:true
		},{
			text:'截止时间',dataIndex:'enddt'
		},{
			text:'目的地',dataIndex:'address'
		},{
			text:'驾驶员',dataIndex:'jianame'
		},{
			text:'起始公里',dataIndex:'kmstart'
		},{
			text:'起始公里',dataIndex:'kmend'
		},{
			text:'归还时间',dataIndex:'returndt'
		},{
			text:'申请人',dataIndex:'optname'
		},{
			text:'操作时间',dataIndex:'optdt'
		},{
			text:'状态',dataIndex:'statustext'
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
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({
				'key':s,
				'dt':get('dt1_{rand}').value
			},true);
		},
		daochu:function(){
			a.exceldown();
		},
		view:function(){
			var d=a.changedata;
			openxiangs('车辆预定',modenum,d.id);
		},
		clickwin:function(){
			openinput('车辆预定',modenum);
		}
	};
	js.initbtn(c);
});
</script>
<div>
<table width="100%">
<tr>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
	</td>
	
	<td nowrap>
		<div style="width:150px"  class="input-group">
			<input placeholder="使用/申请日期" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="return js.selectdate(this,'dt1_{rand}')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	
	<td style="padding-left:10px">
		<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="车牌/使用者/申请人">
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
