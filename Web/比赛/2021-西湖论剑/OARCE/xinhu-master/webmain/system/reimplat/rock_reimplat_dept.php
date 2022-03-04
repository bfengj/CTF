<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){	
	var a = $('#view1_{rand}').bootstable({
		url:js.getajaxurl('deptwxdata','{mode}','{dir}'),
		tree:true,celleditor:true,tablename:'dept',
		columns:[{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'上级id',dataIndex:'pid'
		},{
			text:'排序号',dataIndex:'sort'
		},{
			text:'ID',dataIndex:'id'	
		}]
	});

	var b = $('#view2_{rand}').bootstable({
		url:js.getajaxurl('deptdata','{mode}','{dir}'),
		tree:true,
		columns:[{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'上级id',dataIndex:'pid'
		},{
			text:'负责人',dataIndex:'headman'
		},{
			text:'排序号',dataIndex:'sort'
		},{
			text:'ID',dataIndex:'id'	
		}]
	});
	var c = {
		relad:function(){
			a.reload();
			b.reload();
		},
		anaytodept:function(){
			js.loading('更新中...');
			js.ajax(js.getajaxurl('updatealldept','{mode}', '{dir}'),false, function(ret){
				if(ret.success){
					js.msgok('更新成功');
					a.reload();
				}else{
					js.msgerror(ret.msg);
				}
			},'get,json');
		}
	}
	js.initbtn(c);
});
</script>



<table width="100%">
<tr valign="top">
	<td width="50%">
		<div>
			<button class="btn btn-default" click="relad,0" type="button">刷新</button>&nbsp;&nbsp;
		</div>
		<div class="blank10"></div>
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">REIM即时通讯平台上组织结构</h3></div>
			<div id="view1_{rand}"></div>
			
		</div>
	</td>
	<td width="10"><div style="width:10px;overflow:hidden"></div></td>
	<td width="50%">
		<div>
			<button class="btn btn-default" click="anaytodept" type="button">系统上部门一键同步到REIM平台上</button>
		</div>
		<div class="blank10"></div>
		<div class="panel panel-success">
			<div class="panel-heading"><h3 class="panel-title">系统上组织结构</h3></div>
			
			<div id="view2_{rand}"></div>
		</div>
	</td>
</tr>
</table>