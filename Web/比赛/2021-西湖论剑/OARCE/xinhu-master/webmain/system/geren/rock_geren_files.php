<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var fileid=params.fileid;
	if(!fileid)fileid='';
	var a = $('#veiw_{rand}').bootstable({
		tablename:'files',sort:'id',dir:'desc',modedir:'{mode}:{dir}',params:{'fileid':fileid},fanye:true,
		storebeforeaction:'filelogs_before',
		columns:[{
			text:'操作人',dataIndex:'optname',sortable:true
		},{
			text:'IP',dataIndex:'ip'
		},{
			text:'浏览器',dataIndex:'web'
		},{
			text:'类型',dataIndex:'type',sortable:true,renderer:function(v){
				var s='&nbsp;';
				if(v=='0')s='预览';
				if(v=='1')s='下载';
				if(v=='2')s='在线编辑';
				return s;
			}
		},{
			text:'时间',dataIndex:'optdt',sortable:true
		}],
		itemclick:function(){
			btn(false);
		}
	});
	
	
	var c = {
		del:function(){
			a.del({url:js.getajaxurl('delfilelogs','{mode}','{dir}')});
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		}
	};
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
	}
	js.initbtn(c);
	$('#showfiels{rand}').html('<h4>文件“'+jm.base64decode(params.filename)+'”的操作记录：</h4>');
	if(admintype=='0')$('#del_{rand}').hide();
});
</script>


<div>


<table width="100%"><tr>
	<td  width="80%">
		<div id="showfiels{rand}"></div>
	</td>
	<td style="padding-left:10px">
		
	</td>
	
	
	
	<td></td>
	<td align="right" nowrap>
	
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>