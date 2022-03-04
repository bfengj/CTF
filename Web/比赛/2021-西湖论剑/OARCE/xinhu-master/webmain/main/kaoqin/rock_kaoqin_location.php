<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	var a = $('#view_{rand}').bootstable({
		tablename:'location',celleditor:true,fanye:true,
		modedir:'{mode}:{dir}',params:{'atype':atype},modenum:'kqdw',
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left'
		},{
			text:'姓名',dataIndex:'name'
		},{
			text:'打卡时间',dataIndex:'optdt',sortable:true
		},{
			text:'星期',dataIndex:'week'
		},{
			text:'定位类型',dataIndex:'type',sortable:true
		},{
			text:'地址',dataIndex:'label'
		},{
			text:'精确到',dataIndex:'precision'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'图片',dataIndex:'imgpath',renderer:function(v){
				var s='&nbsp;';
				if(!isempt(v))s='<img height="60" onclick="$.imgview({url:this.src})" src="'+v+'">';
				return s;
			}
		},{
			text:'',dataIndex:'opt',renderer:function(v,d){
				var s='&nbsp;';
				if(!isempt(d.location_x))s='<a onclick="js.locationshow('+d.id+')" href="javascript:;">地图上打开</a>';
				return s;
			}
		}]
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt1:get('dt1_{rand}').value,dt2:get('dt2_{rand}').value},true);
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
		xiashu:function(o1){
			if(atype=='my'){
				o1.value='我的记录';
				atype = 'down';
				nowtabssettext('下属定位记录');
			}else{
				o1.value='下属记录';
				atype = 'my';
				nowtabssettext('我的定位记录');
			}
			a.setparams({atype:atype}, true);
		}
	};
	js.initbtn(c);
	if(atype=='my')$('#down_{rand}').show();
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
	<td  style="padding-left:10px">
		<input class="form-control" style="width:150px" id="key_{rand}"   placeholder="姓名/部门">
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td style="padding-left:10px">
		<input class="btn btn-default" click="xiashu" id="down_{rand}" style="display:none" value="下属记录" type="button">
	</td>
	<td width="80%"></td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">定位记录并不能使用做考勤打卡，目前只是用于外勤定位打卡使用。</div>
