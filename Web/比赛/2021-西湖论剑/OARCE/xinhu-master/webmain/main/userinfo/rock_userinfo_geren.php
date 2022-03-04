<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var modenum='userinfo';
	var a = $('#view_{rand}').bootstable({
		tablename:'userinfo',modenum:modenum,params:{atype:'my'},
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left',sortable:true
		},{
			text:'姓名',dataIndex:'name',sortable:true
		},{
			text:'性别',dataIndex:'sex'
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'状态',dataIndex:'state',sortable:true
		},{
			text:'入职日期',dataIndex:'workdate',sortable:true
		},{
			text:'转正日期',dataIndex:'positivedt',sortable:true
		},{
			text:'电话',dataIndex:'tel'
		},{
			text:'手机号',dataIndex:'mobile'
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
	
	function btn(bo){
		get('xiang_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
	}
	
	var c = {
		view:function(){
			var d=a.changedata;
			openxiangs('个人资料',modenum,d.id);
		},
		edit:function(){
			openinput('个人资料',modenum,a.changeid+'&optlx=my');
		}
	};
	js.initbtn(c);
});
</script>
<div>
<table width="100%">
<tr>
	<td nowrap>
		
	</td>
	<td  style="padding-left:10px">
		
	</td>

	<td width="90%"></td>
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp;  
		<button class="btn btn-default" id="edit_{rand}" click="edit" disabled type="button">编辑</button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">个人资料请认真填写！</div>
