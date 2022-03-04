<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	var a = $('#view_{rand}').bootstable({
		tablename:'kqdkjl',celleditor:true,fanye:true,modenum:'kqdkjl',sort:'id',dir:'desc',
		modedir:'{mode}:{dir}',params:{'atype':atype},storeafteraction:'kqdkjlaftershow',
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left'
		},{
			text:'姓名',dataIndex:'name'
		},{
			text:'打卡时间',dataIndex:'dkdt',sortable:true
		},{
			text:'星期',dataIndex:'week'
		},{
			text:'类型',dataIndex:'type',sortable:true
		},{
			text:'添加时间',dataIndex:'optdt',sortable:true
		},{
			text:'IP',dataIndex:'ip'
		},{
			text:'MAC地址',dataIndex:'mac'
		},{
			text:'打卡位置',dataIndex:'address'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'图片',dataIndex:'imgpath',renderer:function(v){
				var s='&nbsp;';
				if(!isempt(v))s='<img height="60" onclick="$.imgview({url:this.src})" src="'+v+'">';
				return s;
			}
		}],
		itemdblclick:function(d){
			//openxiang('kqdkjl',d.id);
		},
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		},
		load:function(d){
			if(d.qybo || d.ddbo){
				var o = $('#huoqbtsn{rand}');
				o.parent().show();
				var str = '';
				if(d.qybo)str='企业微信';
				if(d.ddbo && d.qybo)str+='/';
				if(d.ddbo)str+='钉钉';
				o.val('从'+str+'获取打卡数据');
			}
		}
	});
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
	}
	
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt1:get('dt1_{rand}').value,dt2:get('dt2_{rand}').value},true);
		},
		delss:function(){
			a.del();
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'',
				'btnobj':o1
			});
		},
		adddaka:function(){
			var h = $.bootsform({
				title:'打卡记录',height:240,width:380,
				tablename:'kqdkjl',isedit:0,submitfields:'dkdt,uid,explain',
				params:{otherfields:'type=3,optdt={now}'},
				items:[{
					labelText:'人员',name:'recename',required:true,type:'changeuser',changeuser:{
						type:'user',idname:'uid',title:'选择人员'
					},clearbool:true
				},{
					name:'uid',type:'hidden'
				},{
					labelText:'打卡时间',name:'dkdt',type:'date',view:'datetime',required:true
				},{
					labelText:'说明',name:'explain',type:'textarea',height:60
				}],
				success:function(){
					a.reload();
				}
			});
		},
		daoru:function(){
			//dkjlmanagesss = a;
			//addtabs({num:'admindkjlpl',url:'main,kaoqin,dkjlpl',name:'导入打卡记录'});
			managelistkqdkjl = a;
			addtabs({num:'daorukqdkjl',url:'flow,input,daoru,modenum=kqdkjl',icons:'plus',name:'导入打卡记录'});
		},
		xiashu:function(o1){
			if(atype=='my'){
				o1.value='我的记录';
				atype = 'down';
				nowtabssettext('下属打卡记录');
			}else{
				o1.value='下属记录';
				atype = 'my';
				nowtabssettext('我的打卡记录');
			}
			a.setparams({atype:atype}, true);
		},
		huqodidn:function(){
			js.msg('wait','获取中...');
			var dt1 = get('dt1_{rand}').value;
			var dt2 = get('dt2_{rand}').value;
			js.ajax(js.getajaxurl('getdkjl','{mode}', '{dir}'),{dt1:dt1,dt2:dt2,atype:atype}, function(d){
				if(d.success){
					js.msg('success', d.data);
					a.reload();
				}
			},'post,json');
		}
	};
	if(atype=='all')$('#btnss{rand}').show();
	if(atype=='my')$('#down_{rand}').show();
	
	js.initbtn(c);
});
</script>
<div>
<table width="100%"><tr>
	<td nowrap>日期&nbsp;</td>
	<td nowrap>
		<div style="width:140px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td nowrap>&nbsp;至&nbsp;</td>
	<td nowrap>
		<div style="width:140px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td style="padding-left:10px">
		<input class="form-control" style="width:150px" id="key_{rand}"   placeholder="姓名/部门">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
	<td style="padding-left:10px;display:none">
		<input class="btn btn-default" id="huoqbtsn{rand}" click="huqodidn" value="从企业微信获取打卡数据" type="button">
	</td>
	<td style="padding-left:10px" width="80%">
		<input class="btn btn-default" click="xiashu" id="down_{rand}" style="display:none" value="下属记录" type="button">
	</td>
	<td align="right" id="btnss{rand}" style="display:none" nowrap>
		<button class="btn btn-default" click="daoru" type="button">导入</button>&nbsp;
		<button class="btn btn-default" click="adddaka" type="button"><i class="icon-plus"></i> 新增</button>&nbsp;
		<button class="btn btn-danger" id="del_{rand}" disabled click="delss" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
