<?php
/**
*	模块：assetm.固定资产
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.固定资产]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'assetm',modename='固定资产',isflow=0,modeid='41',atype = params.atype,pnum=params.pnum,modenames='',listname='YXNzZXRt';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

if(pnum=='all'){
	bootparams.checked=true;
	bootparams.autoLoad=false;

	var shtm = '<table width="100%"><tr valign="top"><td><div style="border:1px #cccccc solid;width:220px"><div id="optionview_{rand}" style="height:400px;overflow:auto;"></div></div></td><td width="8" nowrap><div style="width:8px;overflow:hidden"></div></td><td width="95%"><div id="viewassetm_{rand}"></div></td></tr></table>';
	$('#viewassetm_{rand}').after(shtm).remove();
	c.stable = 'assetm';
	c.optionview = 'optionview_{rand}';
	c.optionnum = 'assetstype';
	c.title = '资产分类';
	c.rand = '{rand}';

	var c = new optionclass(c);

	$('#'+c.optionview+'').css('height',''+(viewheight-120)+'px');
	$('#tdright_{rand}').prepend(c.getbtnstr('所有资产','allshow')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('打印二维码','prinwem')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend('<span id="megss{rand}"></span>&nbsp;&nbsp;');
	setTimeout(function(){c.mobj=a},5);//延迟设置，不然不能双击分类搜索

	c.prinwem=function(){
		var sid = a.getchecked();
		if(sid==''){
			js.msg('msg','没有选中记录');
			return;
		}
		var url = '?a=printewm&m=assetm&d=main&sid='+sid+'';
		window.open(url);
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
<div id="viewassetm_{rand}"></div>
<!--HTMLend-->