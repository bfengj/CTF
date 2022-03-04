<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#admin_{rand}').bootstable({
		tablename:'admin',modenum:'user',celleditor:true,sort:'sort',dir:'asc',fanye:true,
		storebeforeaction:'coguserbeforeshow',storeafteraction:'coguseraftershow',modedir:'{mode}:{dir}',params:{atype:'all'},
		columns:[{
			text:'头像',dataIndex:'face',renderer:function(v,d){
				if(isempt(v))v='images/noface.png';
				return '<img src="'+v+'" height="24" width="24">';
			}
		},{
			text:'部门',dataIndex:'deptallname',align:'left'
		},{
			text:'姓名',dataIndex:'name',sortable:true
		},{
			text:'用户名',dataIndex:'user'
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'邮箱',dataIndex:'email',editor:true
		},{
			text:'邮箱密码',dataIndex:'emailpass',editor:!ISDEMO
		},{
			text:'状态',dataIndex:'status',type:'checkbox',editor:true,sortable:true
		},{
			text:'ID',dataIndex:'id'	
		}]
	});
	
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		}
	};
	
	js.initbtn(c);
});
</script>



<div>

<table width="100%"><tr>
	<td>
		<div class="input-group" style="width:250px">
			<input class="form-control" id="key_{rand}"   placeholder="姓名/部门/职位/用户名">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>
	</td>
	
	<td width="80%"></td>
	<td align="right" nowrap>
		<a class="btn btn-default" href="<?=URLY?>view_email.html" target="_blank">?查看邮件帮助</a>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="admin_{rand}"></div>
<div class="tishi">此功能设置每个用户收发邮件的邮箱帐号密码，添加用户到用户管理那添加。</div>
