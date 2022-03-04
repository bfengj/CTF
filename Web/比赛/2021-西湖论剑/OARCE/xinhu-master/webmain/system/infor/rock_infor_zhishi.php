<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	
	

	var a = $('#view_{rand}').bootstable({
		tablename:'knowledge',celleditor:true,autoLoad:false,modenum:'knowledge',fanye:true,
		columns:[{
			text:'标题',dataIndex:'title',editor:false,align:'left'
		},{
			text:'分类',dataIndex:'typename'
		},{
			text:'添加时间',dataIndex:'adddt',sortable:true
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
			text:'操作人',dataIndex:'optname'
		},{
			text:'排序',dataIndex:'sort',sortable:true,editor:true
		},{
			text:'ID',dataIndex:'id'
		},{
			text:'',dataIndex:'caozuo'
		}],
		itemdblclick:function(d){
			openxiangs(d.title,'knowledge', d.id);
		}
	});


	var c = {
		del:function(){
			a.del();
		},
		adds:function(){
			openinput('知识','knowledge');
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		daochu:function(){
			a.exceldown();
		},
		mobj:a,
		title:'知识分类',
		stable:'knowledge',
		optionview:'optionview_{rand}',
		optionnum:'knowledgetype',
		rand:'{rand}'
	};
	
	var c = new optionclass(c);
	
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
<td width="10" nowrap><div style="width:10px">&nbsp;</div></td>
<td width="95%">	
	<div>
	<table width="100%"><tr>
		<td align="left" nowrap>
			<button class="btn btn-primary" click="adds"  type="button"><i class="icon-plus"></i> 新增</button>&nbsp; 
			<button class="btn btn-default" click="allshow"  type="button">所有知识</button>&nbsp; 
			
		</td>
		
		<td style="padding-left:10px">
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="标题/分类">
		</td>
		<td style="padding-left:10px">
			<button class="btn btn-default" click="search" type="button">搜索</button> 
		</td>
		<td width="90%">
			&nbsp;&nbsp;<span id="megss{rand}"></span>
		</td>
		<td align="right">
			<button class="btn btn-default"  click="daochu" type="button">导出</button>
		</td>
	</tr></table>
	</div>
	<div class="blank10"></div>
	<div id="view_{rand}"></div>
</td>
</tr>
</table>