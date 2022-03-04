<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#veiw_{rand}').bootstable({
		tablename:'email_cog',celleditor:true,sort:'sort',dir:'asc',
		columns:[{
			text:'编号',dataIndex:'num'
		},{
			text:'发送名称',dataIndex:'name'
		},{
			text:'SMTP服务器',dataIndex:'serversmtp'
		},{
			text:'SMTP服务器端口',dataIndex:'serverport'
		},{
			text:'邮箱帐号',dataIndex:'emailname'
		},{
			text:'连接方式',dataIndex:'secure'
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'ID',dataIndex:'id'	
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
			a.del();
		},
		clickwin:function(o1,lx){
			var h = $.bootsform({
				title:'系统邮件帐号',height:400,width:500,
				tablename:'email_cog',isedit:lx,params:{int_filestype:'sort,serverport'},
				submitfields:'serversmtp,name,serverport,emailname,num,secure,sort',url:js.getajaxurl('publicsave','email','system'),beforesaveaction:'savebeforecog',
				items:[{
					labelText:'编号',name:'num',required:true
				},{
					labelText:'名称',name:'name',required:true
				},{
					labelText:'SMTP服务器',name:'serversmtp',required:true
				},{
					labelText:'SMTP服务器端口',name:'serverport',required:true,type:'number'
				},{
					labelText:'邮箱帐号',name:'emailname',required:true
				},{
					labelText:'邮箱密码',name:'emailpass'
				},{
					labelText:'连接方式',name:'secure',store:[['','默认'],['ssl','ssl']],type:'select',displayfields:1,valuefields:0
				},{
					labelText:'序号',name:'sort',type:'number',value:'0'
				}],
				success:function(){
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
			}
			h.getField('name').focus();
		},
		refresh:function(){
			a.reload();
		},
		yunx:function(){
			if(ISDEMO){js.msg('success','demo上就不要测试，我们都测试通过的');return;}
			var url = js.getajaxurl('testsend','{mode}','{dir}');
			js.ajax(url,{id:a.changeid},function(s){
				js.msg('success', s);
			},'get',false,'测试发送中...');
		}
	};
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
		get('yun_{rand}').disabled = bo;
	}
	js.initbtn(c);
});
</script>


<div>


<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button> &nbsp; 
		<button class="btn btn-default" click="refresh" type="button"><i class="icon-refresh"></i> 刷新</button> &nbsp;
	</td>
	
	
	
	<td width="80%"></td>
	<td align="right" nowrap>
		
		<button class="btn btn-default" id="yun_{rand}" click="yunx" disabled type="button">测试发送</button> &nbsp; 
		
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
<div class="tishi">提示：此功能是设置系统邮件发送的帐号，如一些邮件的提醒功能！测试发送是发送到当前登录用户的邮箱上！</div>
