<?php
/**
*	模块：customer.客户管理
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.客户管理]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'customer',modename='客户管理',isflow=0,modeid='7',atype = params.atype,pnum=params.pnum,modenames='',listname='Y3VzdG9tZXI:';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

var chengsuid = '';
if(atype!='my')$('#daoruspan_{rand}').remove();
if(pnum=='' || pnum=='all'){
	bootparams.checked = true;

	c.move=function(){
		var s= a.getchecked();
		if(s==''){js.msg('msg','没有选择记录');return;}
		chengsuid=s;
		js.confirm('是否客户转移给其他人，并客户下的合同和待收付款单和销售机会和销售单同时转移？', function(jg){
			if(jg=='yes')c.moveto();
		});
	}
	c.movetoss=function(sna,toid){
		js.ajax(js.getajaxurl('movecust',modenum,'main'),{'toid':toid,'sid':chengsuid},function(s){
			a.reload();
		},'post',false,'转移给:'+sna+'...,转移成功');
	}
	c.moveto=function(sid){
		var cans = {
			type:'user',
			title:'转移给...',
			callback:function(sna,sid){
				if(sid)c.movetoss(sna,sid);
			}
		}
		setTimeout(function(){js.getuser(cans);},10);
	}
	$('#tdright_{rand}').append('&nbsp; '+c.getbtnstr('客户转移','move'));
}

if(atype=='my'){
	$('#tdright_{rand}').append('&nbsp; '+c.getbtnstr('共享','shates'));
	c.shates=function(){
		var s= a.getchecked();
		if(s==''){js.msg('msg','没有选择记录');return;}
		chengsuid=s;
		var cans = {
			type:'usercheck',
			title:'共享给...',
			callback:function(sna,sid){
				c.shatess(sna,sid);
			}
		}
		js.getuser(cans)
	}
	c.shatess=function(sna,sid){
		js.ajax(c.getacturl('shateto'),{'sna':sna,'sid':sid,khid:chengsuid},function(s){
			a.reload();
		},'post',false,'共享给:'+sna+'...,共享成功');
	}
}

if(pnum!='gys' && pnum!='')$('#tdleft_{rand}').hide();
if(pnum=='dist'){
	bootparams.checked = true;
	c.distss=function(o1,lx){
		var s = a.getchecked();
		if(s==''){js.msg('msg','没有选中行');return;}
		if(lx==0){
			js.confirm('确定要将选中标为未分配吗？',function(jg){
				if(jg=='yes')c.distssok(s, '','', 0);
			});
			return;
		}
		var cans = {
			type:'user',
			title:'选中分配给...',
			callback:function(sna,sid){
				if(sna=='')return;
				setTimeout(function(){
					js.confirm('确定要将选中记录分配给：['+sna+']吗？',function(jg){
						if(jg=='yes')c.distssok(s, sna,sid,1);
					});
				},10);
			}
		};
		js.getuser(cans);
	}
	c.distssok=function(s, sna,sid, lx){
		js.ajax(js.getajaxurl('distcust',modenum,'main'),{sid:s,sname:sna,snid:sid,lx:lx},function(s){
			a.reload();
		},'post','','处理中...,处理成功');
	}
	$('#tdright_{rand}').prepend(c.getbtnstr('标为未分配','distss,0')+'&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('选中分配给','distss,1')+'&nbsp;&nbsp;');
}

if(pnum!='gys' && pnum!='ghai'){
	$('#tdright_{rand}').prepend(c.getbtnstr('重新统计金额','retotal')+'&nbsp;');

	c.retotal=function(){
		js.ajax(js.getajaxurl('retotal',modenum,'main'),{},function(s){
			a.reload();
		},'get',false,'统计中...,统计完成')
	}
}
if(pnum=='gys'){	
	modename = '供应商管理';
	c.clickwin=function(o1,lx){
		openinput(modename,modenum,'0&def_isgys=1','opegs{rand}');
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
		<td><select class="form-control" style="width:120px;border-left:0;border-radius:0;" id="selstatus_{rand}"><option value="">-全部状态-</option><option style="color:blue" value="0">待处理</option><option style="color:green" value="1">已审核</option><option style="color:red" value="2">不同意</option><option style="color:#888888" value="5">已作废</option></select></td>
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
<div id="viewcustomer_{rand}"></div>
<!--HTMLend-->