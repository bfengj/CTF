<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	

	var a = $('#veiw_{rand}').bootstable({
		tablename:'wouser',fanye:true,
		modedir:'{mode}:{dir}',
		storebeforeaction:'wouser_before',
		columns:[{
			text:'头像',dataIndex:'headimgurl',renderer:function(v){
				return '<img src="'+v+'" width="40">';
			}
		},{
			text:'微信昵称',dataIndex:'nickname'
		},{
			text:'openid',dataIndex:'openid'
		},{
			text:'性别',dataIndex:'sex',sortable:true,renderer:function(v){
				if(v=='1')v='男';
				if(v=='2')v='女';
				return v;
			}
		},{
			text:'国家',dataIndex:'country'
		},{
			text:'省份',dataIndex:'province',sortable:true
		},{
			text:'城市',dataIndex:'city',sortable:true
		},{
			text:'授权时间',dataIndex:'adddt',sortable:true
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
			text:'ID',dataIndex:'id',sortable:true
		}],
		itemclick:function(){
			btn(false); 
		},
		beforeload:function(){
			btn(true); 
		}
	});
	
	//编辑和删除按钮可用状态切换
	function btn(bo){
		
	}
	

	var c={
		
		
		refresh:function(){
			a.reload();//刷新列表的方法
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		}
	};
	
	js.initbtn(c); //这个是将上面的方法绑定到对应按钮上
});
</script>
<div>


<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-default" click="refresh" type="button"><i class="icon-refresh"></i> 刷新</button> &nbsp; 
	</td>
	
	<td width="80%">
	
		<div class="input-group" style="width:220px;">
			<input class="form-control" id="key_{rand}"   placeholder="昵称/城市/省份">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>

	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" id="fstest_{rand}" disabled click="testcs" type="button">测试发模板消息</button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>