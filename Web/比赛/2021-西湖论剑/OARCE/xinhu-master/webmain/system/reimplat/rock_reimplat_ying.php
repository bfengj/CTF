<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'dept',sort:'sort',dir:'asc',
		url:js.getajaxurl('agentdata','{mode}','{dir}'),
		columns:[{
			text:'应用Logo',dataIndex:'picurl',renderer:function(v){
				if(isempt(v))v='images/noface.png';
				return '<img src="'+v+'" height="30" width="30">';
			}
		},{
			text:'编号',dataIndex:'num'
		},{
			text:'名称',dataIndex:'name'
		},{
			text:'类型',dataIndex:'types'
		},{
			text:'状态',dataIndex:'status',renderer:function(v){
				if(v=='0')return '停用';
				if(v=='1')return '启用';
				if(v=='2')return '仅PC端';
				if(v=='3')return '仅手机端';
			}
		},{
			text:'应用地址',dataIndex:'url',align:'left',renderer:function(v,d){
				var str='';
				if(d.url)str='PC端：'+d.url+'<br>';
				if(d.murl)str+='手机端：'+d.murl+'';
				return str;
			}
		},{
			text:'直接打开',dataIndex:'iszy',sortable:true,type:'checkbox'
		},{
			text:'适用对象',dataIndex:'recename'
		}],
		itemclick:function(){
			btn(false);
		}
	});
	
	function btn(bo){
		get('faxis_{rand}').disabled = bo;
		//get('del_{rand}').disabled = bo;
		//get('edit_{rand}').disabled = bo;
	}
	
	var c = {
		reloads:function(){
			a.reload();
		},
		faxiaox:function(){
			var d=a.changedata;
			js.prompt('向应用['+d.name+']发送消息','消息内容，接收人是“<b>'+adminname+'</b>”',function(lx,txt){
				if(lx=='yes'&&txt)c.sheniokx(d.name,txt)
			});
		},
		sheniokx:function(na,txt){
			js.loading('发送中...');
			js.ajax(js.getajaxurl('sendmsg','{mode}', '{dir}'),{name:na,msg:txt}, function(d){
				if(d.success){
					js.msgok('发送成功');
				}else{
					js.msgerror(d.msg);
				}
			},'post,json');
		},
	};

	js.initbtn(c);
});
</script>
<div>
<table width="100%">
<tr>
<td nowrap>
<button class="btn btn-default" click="reloads,0" type="button">刷新</button>&nbsp;&nbsp;
</td>
<td width="90%" style="padding-left:10px">应用管理请到REIM即时通讯平台下操作</td>
<td align="right" nowrap>
	<button class="btn btn-info" click="faxiaox" disabled id="faxis_{rand}" type="button">发送消息</button>
</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
