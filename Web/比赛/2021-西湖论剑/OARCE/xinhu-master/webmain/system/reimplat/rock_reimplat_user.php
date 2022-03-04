<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'admin',sort:'sort',dir:'asc',celleditor:false,fanye:true,checked:true,
		storebeforeaction:'beforeusershow',storeafteraction:'afterusershow',url:publicstore('{mode}','{dir}'),
		columns:[{
			text:'头像',dataIndex:'face',renderer:function(v){
				if(isempt(v))v='images/noface.png';
				return '<img src="'+v+'" height="24" width="24">';
			}
		},{
			text:'用户名',dataIndex:'user'
		},{
			text:'姓名',dataIndex:'name'
		},{
			text:'部门',dataIndex:'deptname'
		},{
			text:'多部门',dataIndex:'deptnames'
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'启用',dataIndex:'status',type:'checkbox',sortable:true
		},{
			text:'性别',dataIndex:'sex'
		},{
			text:'平台状态',dataIndex:'zt',align:'left',renderer:function(v,d){
				var s='';
				if(d.iscj==1){
					s='<a href="javascript:;" onclick="upsse{rand}.upuser('+d.id+')">[更新]</a>';
					if(d.isgz==1)s+='<font color=green>已关注</font>';
					if(d.isgz==0)s+='未关注';
					if(d.isgz==2)s+='<font color=#888888>已禁用</font>';
					if(d.isgc>0)s+='<font color=red>需更新('+d.isgcstr+')</font>';
				}else{
					s='<a href="javascript:;" style="color:red" onclick="upsse{rand}.upuser('+d.id+')">[创建]</a>';
				}
				return s;
			}
		},{
			text:'手机号',dataIndex:'mobile'
		},{
			text:'邮箱',dataIndex:'email'
		},{
			text:'排序号',dataIndex:'sort'
		},{
			text:'ID',dataIndex:'id'	
		}],
		load:function(d){
			var s='';
			if(d.notstr!='')s='REIM即时通讯漂亮有系统有不存在的用户：<font color=red>'+d.notstr+'</font>，请点按钮删除';
			$('#showmsg{rand}').html(s);
		}
	});
	
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		faxiaox:function(){
			
		},
		getlist:function(){
			js.msg('wait','获取中...');
			js.ajax(js.getajaxurl('reloaduser','{mode}', '{dir}'),{}, function(d){
				if(d.success){
					js.msg('success', '获取成功');
					a.reload();
				}else{
					js.msg('msg', d.msg);
				}
			},'get,json');
		},
		delaluser:function(){
			js.confirm('确定要要一键删除不存在用户吗？',function(jg){
				if(jg=='yes')c.deleteusers();
			});
		},
		deleteusers:function(){
			js.loading('删除中...');
			js.ajax(js.getajaxurl('delalluser','{mode}', '{dir}'),false, function(ret){
				if(ret.success){
					js.msgok('删除成功');
					a.reload();
				}else{
					js.msgerror(ret.msg);
				}
			},'get,json');
		},
		upuser:function(id){
			js.loading('更新中...');
			js.ajax(js.getajaxurl('updateuser','{mode}', '{dir}'),{id:id}, function(ret){
				if(ret.success){
					js.msgok('更新成功');
					a.reload();
				}else{
					js.msgerror(ret.msg);
				}
			},'get,json');
		},
		updatess:function(){
			if(this.plbool)return;
			var d = a.getcheckdata();
			if(d.length<=0){
				js.msg('msg','请先用复选框选中行');
				return;
			}
			this.checkd = d;
			this.plliangsos(0);
		},
		plliangsos:function(oi){
			var len = this.checkd.length;
			if(!get('plchushumm'))js.loading('<span id="plchushumm"></span>');
			$('#plchushumm').html('更新中('+len+'/'+(oi+1)+')...');
			if(oi>=len){
				js.msgok('更新完成');
				a.reload();
				this.plbool=false;
				return;
			}
			var ds = this.checkd[oi];
			js.ajax(js.getajaxurl('updateuser','{mode}', '{dir}'),{id:ds.id}, function(ret){
				if(ret.success){
					c.plliangsos(oi+1);
				}else{
					c.plbool=false;
					$('#plchushumm').html('<font color=red>['+ds.name+']更新失败：'+ret.msg+'</font>');
				}
			},'get,json');
		},
		faxiaox:function(){
			var d=a.changedata;
			if(!d.id){js.msg('msg','请先选中人');return;}
			js.prompt('向人员['+d.name+']发送消息','消息内容',function(lx,txt){
				if(lx=='yes'&&txt)c.sheniokx(d.id,txt)
			});
		},
		sheniokx:function(id,txt){
			js.loading('发送中...');
			js.ajax(js.getajaxurl('senduser','{mode}', '{dir}'),{id:id,msg:txt}, function(d){
				if(d.success){
					js.msgok('发送成功');
				}else{
					js.msgerror(d.msg);
				}
			},'post,json');
		}
	};
	
	upsse{rand} = c;
	
	js.initbtn(c);
	
});
</script>
<div>
<table width="100%">
<tr>
<td><button class="btn btn-default" click="updatess" type="button">更新用户</button></td>
<td style="padding-left:10px"><button class="btn btn-default" click="getlist" type="button">获取REIM通信平台用户</button></td>

<td style="padding-left:10px">
	<div class="input-group" style="width:220px;">
		<input class="form-control" id="key_{rand}"   placeholder="姓名/部门/职位/用户名">
		<span class="input-group-btn">
			<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
		</span>
	</div>
</td>
<td width="90%" style="padding-left:10px"></td>
<td align="right" nowrap>
	<button class="btn btn-info" click="faxiaox" type="button">发消息</button>&nbsp;&nbsp;
	<button class="btn btn-danger" click="delaluser" type="button">删除REIM通信平台上系统不存在的用户</button>
</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div id="showmsg{rand}" class="tishi"></div>