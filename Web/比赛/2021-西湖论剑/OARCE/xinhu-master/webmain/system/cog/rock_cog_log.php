<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var atype=params.atype;
	var a = $('#veiw_{rand}').bootstable({
		tablename:'log',celleditor:true,sort:'id',dir:'desc',modedir:'{mode}:{dir}',params:{'atype':atype},checked:true,fanye:true,
		storebeforeaction:'logbefore',
		columns:[{
			text:'类型',dataIndex:'type'
		},{
			text:'操作人',dataIndex:'optname',sortable:true
		},{
			text:'备注',dataIndex:'remark',align:'left',renderer:function(v,d){
				if(d.url && d.level==2){
					if(d.url.indexOf('http')==0){
						v+='<br>'+d.url+'';
					}else{
						v+='<br><a href="'+d.url+'" target="_blank">查看详情</a>';
					}
				}
				if(d.url && d.level==3){
					if(!ISDEMO)v+='<br>'+d.url+'';
					if(d.result)v+='<br>运行结果：<br>'+d.result+'';
				}
				return v;
			},renderstyle:function(){
				return 'word-wrap:break-word;word-break:break-all;white-space:normal;';
			}
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
			text:'IP',dataIndex:'ip'
		},{
			text:'浏览器',dataIndex:'web'
		},{
			text:'Device',dataIndex:'device'
		},{
			text:'级别',dataIndex:'level'
		},{
			text:'ID',dataIndex:'id',sortable:true
		}],
		rendertr:function(d){
			var s = '';
			if(d.level==2)s='style="color:red"';
			return s;
		}
	});
	

	var c = {
		delss:function(){
			a.del({url:js.getajaxurl('dellog','{mode}','{dir}'),checked:true});
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		daochu:function(){
			a.exceldown();
		},
		qingkong:function(ox,lx){
			var set = ['全部','异步队列'];
			js.confirm('确定要清空'+set[lx]+'的日志记录吗？', function(jg){
				if(jg=='yes')c.qingkongs(lx);
			});
		},
		qingkongs:function(lx){
			js.loading('清空中...');
			$.get(js.getajaxurl('clearlog','{mode}','{dir}',{lx:lx}), function(){
				js.msgok('已清空');
				a.reload();
			});
		}
	};
	js.initbtn(c);
});
</script>


<div>


<table width="100%"><tr>
	<td>
		<input class="form-control" style="width:300px" id="key_{rand}"   placeholder="类型/操作人/浏览器/IP/备注">
	</td>
	<td nowrap style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>&nbsp; 
		<button class="btn btn-default" click="daochu,1" type="button">导出</button>
	</td>
	
	
	
	<td width="80%"></td>
	<td align="right" nowrap>
	
		<button class="btn btn-default" click="qingkong,1" type="button">仅清空异步队列</button>&nbsp;
		<button class="btn btn-default" click="qingkong,0" type="button">清空全部</button>&nbsp;
		<button class="btn btn-danger" id="del_{rand}" click="delss" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>