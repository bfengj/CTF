<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var tabelss = params.table;
	var a = $('#veiw_{rand}').bootstable({
		tablename:'table',celleditor:true,url:js.getajaxurl('tablefields','{mode}','{dir}'),
		params:{'table':tabelss},
		columns:[{
			text:'字段名',dataIndex:'name'
		},{
			text:'类型',dataIndex:'type'
		},{
			text:'类型',dataIndex:'types'
		},{
			text:'是否为空',dataIndex:'isnull'
		},{
			text:'默认值',dataIndex:'dev'
		},{
			text:'说明',dataIndex:'explain',type:'textarea',align:'left'
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
	
	var c = {
		del:function(){
			a.del({
				url:js.getajaxurl('delfields','{mode}','{dir}'),
				params:{table:tabelss}
			});
		},
		clickwin:function(o1,lx){
			var h = $.bootsform({
				title:'字段',height:400,width:500,
				tablename:'',isedit:lx,url:js.getajaxurl('savefields','{mode}','{dir}',{table:tabelss}),
				submitfields:'name,type,explain,lens,isnull,dev',
				items:[{
					labelText:'字段名',name:'name',required:true
				},{
					labelText:'类型',name:'type',type:'select',required:true,store:[['varchar','varchar(字符串)'],['int','int(长整数)'],['smallint','smallint(中整数)'],['tinyint','tinyint(短整数)'],['bigint','bigint(超长整数)'],['date','date(日期)'],['datetime','datetime(日期时间)'],['decimal','decimal(浮点数)'],['mediumint','mediumint(中等整数)'],['text','text(长文本域)']],valuefields:0,displayfields:1
				},{
					labelText:'长度',name:'lens',value:'0'
				},{
					labelText:'默认值',name:'dev'
				},{
					labelText:'说明',name:'explain'
				},{
					name:'isnull',labelText:'是否为空',type:'select',store:[['YES','是'],['NO','否']],valuefields:0,displayfields:1
				}],
				success:function(){
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
				var tys=a.changedata.types.split('(');
				var cd='0';
				if(tys[1])cd=tys[1].replace(')','');
				h.setValue('lens', cd);
			}
			h.getField('name').focus();
		},
		refresh:function(){
			a.reload();
		}
	};
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
	}
	js.initbtn(c);
});
</script>


<div>


<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增字段</button> &nbsp; 
		<button class="btn btn-default" click="refresh" type="button"><i class="icon-refresh"></i> 刷新</button>
	</td>

	
	<td width="80%">
		<font color="red">&nbsp; 数据表字段关系到系统运行，请谨慎操作，</font>更好用的管理请使用其他工具。
	</td>
	<td align="right" nowrap>
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>

