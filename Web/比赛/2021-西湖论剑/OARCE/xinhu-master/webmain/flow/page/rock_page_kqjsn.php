<?php
/**
*	模块：kqjsn.考勤机设备
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.考勤机设备]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'kqjsn',modename='考勤机设备',isflow=0,modeid='70',atype = params.atype,pnum=params.pnum,modenames='',listname='a3Fqc24:';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

bootparams.celleditor = true;
	bootparams.checked = true;
	$('#tdright_{rand}').prepend(c.getbtnstr('选中设备操作 <i class="icon-angle-down"></i>','optbtn')+'&nbsp;');
	
	$('#btnoptbtn_{rand}').rockmenu({
		width:170,top:35,donghua:false,
		data:[{
			name:'设置配置',lx:'config'
		},{
			name:'重启',lx:'reboot'
		},{
			name:'获取所有人员',lx:'getuser'
		},{
			name:'获取设备信息',lx:'getinfo'
		},{
			name:'设置广告图1',lx:'advert1'
		},{
			name:'设置广告图2',lx:'advert2'
		},{
			name:'设置广告图3',lx:'advert3'
		}],
		itemsclick:function(d, i){
			c.sendcmd(0, d.lx);
		}
	});
	c.optbtn=function(){
	}
	c.sendcmd=function(id, type){
		var ids = a.getchecked();
		if(ids==''){js.msg('msg','没用复选框选中记录');return;}
		js.ajax(js.getajaxurl('sendcmd','kaoqinj','main'),{ids:ids,'type':type},function(ret){
			if(!ret.success){
				js.msg('msg', ret.msg);
			}else{
				js.msg('success', ret.data);
			}
		},'get,json',false,'发送中...,已发送');
	}
	
	c.setcolumns('num',{
		'renderer':function(v,d,i){
			return ''+v+' <a onclick="show_{rand}('+i+')" href="javascript:;">管理</a>';
		}
	});
	
	show_{rand}=function(i){
		var d = a.getData(i);
		addtabs({num:'sngl'+d.id+'',name:'考勤机设备['+d.name+']管理',url:'main,kaoqinj,dept,snid='+d.id+''});
	}
	
	c.setcolumns('sort',{
		'editor':true
	});
	
	c.setcolumns('name',{
		'editor':true
	});
	
	c.setcolumns('company',{
		'editor':true
	});
	
	c.setcolumns('status',{
		'editor':true,
		'type':'checkbox'
	});

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
<div id="viewkqjsn_{rand}"></div>
<!--HTMLend-->