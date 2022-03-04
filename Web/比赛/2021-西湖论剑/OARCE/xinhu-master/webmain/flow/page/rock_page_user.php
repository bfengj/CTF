<?php
/**
*	模块：user.用户
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.用户]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'user',modename='用户',isflow=0,modeid='10',atype = params.atype,pnum=params.pnum,modenames='',listname='YWRtaW4:';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

bootparams.statuschange = true;
bootparams.celleditor = (atype=='all');
if(ISDEMO)bootparams.celleditor=false;
c.setcolumns('status',{
	editor:true,
	type:'checkbox',
	editorafter:function(){
		a.reload();
	},
	editorbefore:function(d){
		if(d.id=='1'){
			js.msg('msg','ID=1的用户不能编辑');
			return false;
		}else{
			return true;
		}
	}
});

c.setcolumns('isvcard',{
	editor:true,
	type:'checkbox'
});

c.setcolumns('sex',{
	editor:true,
	editor:true,type:'select',store:[['男','男'],['女','女']]
});

c.setcolumns('sort',{
	editor:true
});
c.setcolumns('tel',{
	editor:true
});
c.setcolumns('face',{
	renderer:function(v,d){
		if(isempt(v))v='images/noface.png';
		return '<img src="'+v+'" id="faceviewabc_'+d.id+'" height="24" width="24">';
	}
});
if(atype=='all'){
	bootparams.checked=true;
	$('#tdright_{rand}').prepend(c.getbtnstr('修改上级','editsuper')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('修改头像','editface','','disabled')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('更新数据','gengxin','success')+'&nbsp;&nbsp;');

	c.gengxin=function(){
		js.loading('更新中用户多会比较慢，耐心等待...');
		$.get(js.getajaxurl('updatedata','admin','system'), function(da){
			js.msgok(da);
		});
	}
	c.editface=function(){
		editfacechang(a.changeid, a.changedata.name);
	}
	bootparams.itemclick=function(){
		get('btneditface_{rand}').disabled=false;
	}
	bootparams.beforeload=function(){
		get('btneditface_{rand}').disabled=true;
	}
	c.editsuper=function(){
		var xid = a.getchecked();
		if(xid==''){js.msg('msg','请先用复选框选择行');return;}
		var cans = {
			type:'usercheck',
			title:'选择新的上级主管',
			callback:function(sna,sid){
				if(sna=='')return;
				js.msg('wait','修改中...');
				js.ajax(c.getacturl('editsuper'),{sna:sna,sid:sid,xid:xid}, function(ret){
					js.msg('success', '修改成功');
					a.reload();
				},'post');
				
			}
		};
		js.getuser(cans);
	}
}

//[自定义区域end]
	c.initpagebefore();
	js.initbtn(c);
	var a = $('#view'+modenum+'_{rand}').bootstable(bootparams);
	c.init();
	
});
</script>
<!--SCRIPTend-->
<!--HTMLstart-->
<div>
	<table width="100%">
	<tr>
		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" disabled type="button"><i class="icon-plus"></i> 新增</button></td>
		
		<td><select class="form-control" style="width:110px;border-top-right-radius:0;border-bottom-right-radius:0;padding:0 2px" id="fields_{rand}"></select></td>
		<td><select class="form-control" style="width:60px;border-radius:0px;border-left:0;padding:0 2px" id="like_{rand}"><option value="0">包含</option><option value="1">等于</option><option value="2">大于等于</option><option value="3">小于等于</option><option value="4">不包含</option></select></td>
		<td><select class="form-control" style="width:130px;border-radius:0;border-left:0;display:none;padding:0 5px" id="selkey_{rand}"><option value="">-请选择-</option></select><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px" id="keygj_{rand}" placeholder="关键词"><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px;display:none;" id="key_{rand}" placeholder="关键字">
		</td>
		
		<td>
			<div style="white-space:nowrap">
			<button style="border-right:0;border-radius:0;border-left:0" class="btn btn-default" click="searchbtn" type="button">搜索</button><button class="btn btn-default" id="downbtn_{rand}" type="button" style="padding-left:8px;padding-right:8px;border-top-left-radius:0;border-bottom-left-radius:0"><i class="icon-angle-down"></i></button> 
			</div>
		</td>
		<td  width="90%" style="padding-left:10px"><div id="changatype{rand}" class="btn-group"></div></td>
	
		<td align="right" id="tdright_{rand}" nowrap>
			<span style="display:none" id="daoruspan_{rand}"><button class="btn btn-default" click="daoru,1" type="button">导入</button>&nbsp;&nbsp;&nbsp;</span><button class="btn btn-default" style="display:none" id="daobtn_{rand}" disabled click="daochu" type="button">导出 <i class="icon-angle-down"></i></button> 
		</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="viewuser_{rand}"></div>
<!--HTMLend-->