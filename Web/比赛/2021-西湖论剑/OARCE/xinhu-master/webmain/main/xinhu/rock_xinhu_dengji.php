<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	
	var a = $('#view_{rand}').bootstable({
		tablename:'oainfo',url:js.getajaxurl('getoainfo','{mode}','{dir}'),statuschange:false,
		columns:[{
			text:'名称',dataIndex:'name',renderer:function(v){
				var str = ''+v.substr(0,2)+'**';
				return str;
			}
		},{
			text:'地址',dataIndex:'url',renderer:function(v){
				var str = ''+v.substr(0,10)+'****'+v.substr(-4)+'';
				return str;
			}
		},{
			text:'验证次数',dataIndex:'yanci'
		},{
			text:'最后验证时间',dataIndex:'lastdt'
		}],
		beforeload:function(){
			btn(true);
		},
		itemclick:function(d){
			btn(false);
		},
		load:function(d){
		
		}
	});
	
	function btn(bo){
		if(ISDEMO)bo=true;
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}
	
	var c={
		reloads:function(){
			a.reload();
		},
		clickwin:function(o1,lx){
			var h = $.bootsform({
				title:'系统登记',height:400,width:500,
				tablename:'sms',isedit:lx,
				url:js.getajaxurl('savedengji','{mode}','{dir}'),
				submitfields:'name,url',
				items:[{
					labelText:'名称',name:'name',required:true,blankText:'需要系统相关'
				},{
					labelText:'系统地址',name:'url',required:true,blankText:'一般是这个系统地址，需外网可以访问，/结尾'
				}],
				success:function(){
					js.msg('success','保存成功');
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
			}
			h.getField('name').focus();
		},
	
		del:function(){
			a.del({url:js.getajaxurl('deldengji','{mode}','{dir}')})
		}
	};
	js.initbtn(c);
	if(ISDEMO)get('btn1_{rand}').disabled=true;
});
</script>
<div>
	<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-primary" id="btn1_{rand}" click="clickwin,0"  type="button"><i class="icon-plus"></i> 新增</button>
		 &nbsp; 
		<button class="btn btn-default"  click="reloads"  type="button"><i class="icon-refresh"></i> 刷新</button>
	</td>
	<td align="right">
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button> &nbsp; 
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button>
	</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">登记系统地址，就是向信呼官网提交你的系统地址，在APP登录前可以快速设置，省去输入地址的繁琐，如你自己编译APP已经都设置好了，就不需要这个登记了。</div>