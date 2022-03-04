<?php
/**
*	模块：userinfo.人员档案
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.人员档案]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'userinfo',modename='人员档案',isflow=0,modeid='29',atype = params.atype,pnum=params.pnum,modenames='工作经历,教育经历',listname='dXNlcmluZm8:';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

if(atype=='all'){
	
	$('#tdright_{rand}').prepend(c.getbtnstr('人员状态编辑','bianjila','','disabled')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('更新数据','gengxin','success')+'&nbsp;&nbsp;');
	c.gengxin=function(){
		js.msg('wait', '更新中...');
		$.get(js.getajaxurl('updatedata','admin','system'), function(da){
			js.msg('success', da);
			a.reload();
		});
	}
	c.bianjila=function(){
		var h = $.bootsform({
			title:'人员状态编辑',height:400,width:400,
			tablename:bootparams.tablename,isedit:1,
			url:this.getacturl('publicsave'),aftersaveaction:'userstateafter',
			submitfields:'workdate,state,quitdt,syenddt,positivedt',
			items:[{
				labelText:'名称',name:'name'
			},{
				labelText:'人员状态',name:'state',type:'select',valuefields:'id',displayfields:'name',store:a.getData('statearr'),required:true
			},{
				labelText:'入职日期',name:'workdate',type:'date',required:true
			},{
				labelText:'离职日期',name:'quitdt',type:'date'
			},{
				labelText:'试用期到',name:'syenddt',type:'date'
			},{
				labelText:'转正日期',name:'positivedt',type:'date'
			}],
			success:function(){
				a.reload();
			}
		});
		h.setValues(a.changedata);
		h.setValue('name',a.changedata.name);
		h.setValue('state',a.changedata.stateval);
		h.isValid();
		return h;
	}
	bootparams.itemclick=function(){
		get('btnbianjila_{rand}').disabled=false;
	}
	bootparams.beforeload=function(){
		get('btnbianjila_{rand}').disabled=true;
	}
	
	$('#viewuserinfo_{rand}').after('<div class="tishi">添加人员档案请到[用户管理]那添加，删除档案，需要先删除用户在删除档案。</div>');
}
$('#tdleft_{rand}').hide();

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
		<td><select class="form-control" style="width:120px;border-left:0;border-radius:0;" id="selstatus_{rand}"><option value="">-全部状态-</option><option style="color:" value="0">试用期</option><option style="color:" value="1">正式</option><option style="color:" value="2">实习生</option><option style="color:" value="3">兼职</option><option style="color:" value="4">临时工</option><option style="color:" value="5">离职</option></select></td>
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
<div id="viewuserinfo_{rand}"></div>
<!--HTMLend-->