<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var a = $('#view_{rand}').bootstable({
		tablename:'table',fanye:true,modedir:'{mode}:{dir}',storebeforeaction:'tablebefore',celleditor:true,
		cellurl:js.getajaxurl('tablesm','{mode}','{dir}'),
		columns:[{
			text:'表名',dataIndex:'id',sortable:true
		},{
			text:'引擎',dataIndex:'engine'
		},{
			text:'总记录数',dataIndex:'rows',sortable:true
		},{
			text:'说明',dataIndex:'explain',editor:true
		},{
			text:'创建时间',dataIndex:'cjsj',sortable:true
		},{
			text:'字符集',dataIndex:'TABLE_COLLATION'
		},{
			text:'更新时间',dataIndex:'gxsj',sortable:true
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
	
	function btn(bo){
		get('edit_{rand}').disabled = bo;
		get('kanbtn_{rand}').disabled = bo;
	}
	var  c={
		clickwin:function(){
			var name=a.changeid;
			addtabs({num:'tablefields'+name+'',url:'system,table,fields,table='+name+'',name:'['+name+']字段管理'});
		},
		kanjili:function(){
			var name=a.changeid;
			addtabs({num:'tablerecord'+name+'',url:'system,table,record,table='+name+'',name:'['+name+']记录'});
		},
		search:function(){
			a.setparams({
				key:get('key_{rand}').value
			},true);
		}
	};
	js.initbtn(c);
});
</script>


<div>
	<table width="100%">
	<tr>
	<td >
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="表名">
	</td>
	
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	
	<td  width="80%" style="padding-left:10px">
		
		
	</td>
	
	
	<td align="right" nowrap>
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>&nbsp;
		<button class="btn btn-default" id="kanbtn_{rand}" click="kanjili" disabled type="button">查看记录</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">数据库表格管理请谨慎操作！</div>
