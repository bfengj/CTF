<?php
/**
*	模块：wordxie.文档协作
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.文档协作]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'wordxie',modename='文档协作',isflow=0,modeid='86',atype = params.atype,pnum=params.pnum,modenames='',listname='d29yZHhpZQ::';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

c.setcolumns('wtype', {
	renderer:function(v,d){
		return '<img src="web/images/fileicons/'+v+'.gif" height="20">';
	}
});
c.setcolumns('name', {
	renderer:function(v,d){
		var ss=' <span class="label label-success label-sm">协作</span>';
		if(!d.xiebool)ss=' <span class="label label-default">只读</span>';
		return ''+v+'.'+d.wtype+''+ss+'';
	}
});
c.setcolumns('temp_opt', {
	renderer:function(v,d,oi){
		var lxs = ',doc,docx,xls,xlsx,ppt,pptx,';
	
		var str = (lxs.indexOf(','+d.wtype+',')>-1 && d.xiebool) ? '&nbsp;<a href="javascript:;" onclick="showvies{rand}('+oi+',3)">编辑</a>&nbsp;<a href="javascript:;" onclick="showvies{rand}('+oi+',1)"><i class="icon-arrow-down"></i></a>' : '';
		return '<a href="javascript:;" onclick="showvies{rand}('+oi+',0)">预览</a>'+str+'';
	},
	text:'&nbsp;'
});
showvies{rand}=function(oi,lx){
	var d=a.getData(oi);
	if(lx==3){
		//js.sendeditoffice(d.fileid);
		js.fileopt(d.fileid,2);
		return;
	}
	if(lx==1){
		js.downshow(d.fileid)
	}else{
		js.yulanfile(d.fileid,d.wtype,d.filepath,d.name);
	}
}
$('#viewwordxie_{rand}').after('<div class="tishi">如没有在线编辑插件，可用下载下来编辑写好了在上传，上传的文档名称需一致。</div>');
$('#tdright_{rand}').prepend(c.getbtnstr('上传写好文件','upxieok')+'&nbsp;');
var btnupobj = get('btnupxieok_{rand}');
btnupobj.disabled=true;
bootparams.itemclick=function(d){
	btnupobj.disabled = (!d.xiebool);	
}
bootparams.load=function(d){
	c.loaddata(d);
	btnupobj.disabled=true;
}
c.upxieok=function(){
	var d = a.changedata;
	js.upload('upfilexiezuo{rand}|upchagneback{rand}',{maxup:'1','title':d.name+'.'+d.wtype,uptype:d.wtype});
}
upchagneback{rand}=function(f){
	var d = a.changedata;
	if(f.name.indexOf(d.name)!=0)return '选择的文件名不一致，必须选['+d.name+'.'+d.wtype+']';
}
upfilexiezuo{rand}=function(d){
	js.ajax(c.getacturl('savefile'),{'id':a.changedata.id,'upfileid':d[0].id},function(s){
		a.reload();
	},'get',false, '保存中...,保存成功');
}
openxieeditfile=function(d){
	js.fileopt(d.fileid,2);
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
<div id="viewwordxie_{rand}"></div>
<!--HTMLend-->