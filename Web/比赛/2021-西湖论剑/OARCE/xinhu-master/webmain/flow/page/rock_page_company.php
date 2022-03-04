<?php
/**
*	模块：company.公司单位
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.公司单位]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'company',modename='公司单位',isflow=0,modeid='63',atype = params.atype,pnum=params.pnum,modenames='',listname='Y29tcGFueQ::';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

bootparams.fanye=false;
bootparams.celleditor=true;
bootparams.tree=true;
c.setcolumns('sort',{
	'editor':true
});
c.setcolumns('city',{
	'editor':true
});
c.setcolumns('oaname',{
	'editor':true
});
c.setcolumns('oanemes',{
	'editor':true
});
c.setcolumns('yuming',{
	'editor':true
});
c.setcolumns('logo',{
	'renderer':function(v){
		if(!isempt(v)){
			v='<img src="'+v+'" width="30" height="30">';
		}else{
			v='&nbsp;';
		}
		return v;
	}
});
<?php if(getconfig('platdwnum') && !COMPANYNUM){?>
bootparams.checked=true;
$('#tdright_{rand}').prepend(c.getbtnstr('生成信息','createinfos')+'&nbsp;&nbsp;');
$('#tdright_{rand}').prepend(c.getbtnstr('更新模块','gengxinmok','success')+'&nbsp;&nbsp;');
c.createinfos=function(){
	js.msgok('生成成功');
	js.ajax(c.getacturl('createinfos'));
}
c.gengxinmok=function(){
	var d = a.changedata;
	if(!d.id){js.msg('msg','请先选择记录');return;}
	if(d.iscreate!=1){js.msg('msg','此单位数据还未创建');return;}
	maincompanya = a;
	addtabs({name:'更新单位数据',num:'createdw'+d.id+'',url:'flow,page,companycreate,cid='+d.id+',isgeng=1'});
}

gotucompany=function(oi){
	var da = a.getData(oi);
	if(isempt(da.num)){
		js.msgerror('请先设置单位编号');
		return;
	}
	if(da.iscreate!=1){
		js.msgerror('请先创建单位数据');
		return;
	}
	var pldz = '<?=getconfig('platurl')?>';
	if(!pldz){
		js.msgerror('没有设置平台地址');
		return;
	}
	window.open(pldz+='?m=login&dwnum='+da.num+'');
}
createcompany=function(oi){
	var da = a.getData(oi);
	if(isempt(da.num)){
		js.msgerror('请先设置单位编号');
		return;
	}
	maincompanya = a;
	addtabs({name:'创建单位数据',num:'createdw'+da.id+'',url:'flow,page,companycreate,cid='+da.id+',isgeng=0'});
}
deletecompany=function(oi,bo1){
	if(!bo1){
		js.confirm('确定要删除此单位数据吗，删除就不能恢复了。',function(jg){
			if(jg=='yes')deletecompany(oi,true);
		});
		return;
	}
	var da = a.getData(oi);
	js.loading('删除中...');
	js.ajax(c.getacturl('deletecompany'),{id:da.id},function(res){
		js.msgok(res);
		a.reload();
	});
}
c.onloadbefore=function(d){
	if(!d.listinfo)return;
	bootparams.columns.push({
		text:'单位数据',dataIndex:'iscreate',align:'left',renderer:function(v,d,oi){
			if(v=='0')return '<span class="label label-danger">未创建</span> <button type="button" onclick="createcompany('+oi+')" class="btn btn-success">创建</button>';
			if(v=='1')return '<span class="label label-success">已创建</span> <button type="button" onclick="deletecompany('+oi+')" class="btn btn-danger btn-xs">删除数据</button>';
		}
	});
	bootparams.columns.push({
		text:'进入数据',dataIndex:'optss',
		renderer:function(v,d,oi){
			return '<button type="button" onclick="gotucompany('+oi+')" class="btn btn-info btn-xs">进入单位</button>';
		}
	});
	a.setColumns(bootparams.columns);
}
<?php }?>

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
<div id="viewcompany_{rand}"></div>
<!--HTMLend-->