<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){

	var modenum='bookborrow';
	var a = $('#view_{rand}').bootstable({
		tablename:modenum,celleditor:true,fanye:true,modenum:modenum,statuschange:false,
		columns:[{
			text:'借阅',dataIndex:'bookname'
		},{
			text:'借阅日期',dataIndex:'jydt',sortable:true
		},{
			text:'预计归还',dataIndex:'yjdt'
		},{
			text:'是否归返',dataIndex:'isgh',sortable:true
		},{
			text:'归还时间',dataIndex:'ghtime'
		},{
			text:'借阅人',dataIndex:'optname'
		},{
			text:'操作时间',dataIndex:'optdt'
		},{
			text:'申请日期',dataIndex:'applydt',sortable:true
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
			openxiangs('图书借阅',modenum,d.id);
		},
		clickwin:function(){
			openinput('图书借阅',modenum);
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
			<input placeholder="借阅日期" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="return js.selectdate(this,'dt1_{rand}')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	
	<td style="padding-left:10px">
		<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="借阅/书名">
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
