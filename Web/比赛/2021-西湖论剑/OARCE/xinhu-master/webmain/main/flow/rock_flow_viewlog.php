<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_log',celleditor:true,params:{num:params.modenum,mid:params.mid},dir:'asc',sort:'id',statuschange:false,
		url:publicstore('{mode}','{dir}'),storebeforeaction:'viewlogshowbefore',
		columns:[{
			text:'动作',dataIndex:'name'
		},{
			text:'处理人',dataIndex:'checkname'
		},{
			text:'状态',dataIndex:'statusname',renderer:function(v,d){
				if(d.color)v='<font color="'+d.color+'">'+v+'</font>';
				return v;
			}
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'浏览器',dataIndex:'web'
		},{
			text:'IP',dataIndex:'ip'
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
			text:'步骤ID',dataIndex:'courseid',sortable:true
		},{
			text:'ID',dataIndex:'id',sortable:true
		}],
		itemclick:function(d){
			btn(false, d);
		},
		beforeload:function(){
			btn(true);
		}
	});
	function btn(bo, d){
		get('del_{rand}').disabled = bo;
	}
	var c = {
		
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		}
	};

	js.initbtn(c);

});
</script>

<div>
	<table width="100%">
	<tr>
	<td align="left">
		<button class="btn btn-default" click="reload"  type="button">刷新</button>
	</td>
	<td align="left"  style="padding:0px 10px;">
		
	</td>
	<td align="right">
		<button class="btn btn-danger" click="del" disabled id="del_{rand}" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">提示：删除将会是彻底删除，不能恢复，请谨慎操作！<div>
